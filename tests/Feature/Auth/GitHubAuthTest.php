<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;
use Mockery;
use Tests\TestCase;

final class GitHubAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_redirected_to_github_oauth_page_with_email_scope(): void
    {
        $response = $this->get(route('auth.github.redirect'));

        $response->assertRedirect();
        $location = (string) $response->headers->get('Location');
        $this->assertStringContainsString('github.com/login/oauth/authorize', $location);
        $this->assertStringContainsString('scope=read%3Auser+user%3Aemail', $location);
    }

    public function test_new_user_can_authenticate_via_github_callback_with_null_password(): void
    {
        $abstractUser = Mockery::mock(SocialiteUserContract::class);
        $abstractUser->shouldReceive('getId')->andReturn('12345678');
        $abstractUser->shouldReceive('getName')->andReturn('Linus Torvalds');
        $abstractUser->shouldReceive('getNickname')->andReturn('torvalds');
        $abstractUser->shouldReceive('getEmail')->andReturn('linus@example.com');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatars.githubusercontent.com/u/12345678');

        $provider = Mockery::mock(GithubProvider::class);
        $provider->shouldReceive('scopes')->with(['read:user', 'user:email'])->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('github')->andReturn($provider);

        $response = $this->get(route('auth.github.callback', ['code' => 'fake-auth-code']));

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated();

        $user = User::query()->where('github_id', '12345678')->first();
        $this->assertNotNull($user);
        $this->assertSame('Linus Torvalds', $user->name);
        $this->assertSame('linus@example.com', $user->email);
        $this->assertNull($user->password); // Clean passwordless OAuth, no dummy password!
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_user_creation_is_rejected_if_github_provides_no_verified_email(): void
    {
        $abstractUser = Mockery::mock(SocialiteUserContract::class);
        $abstractUser->shouldReceive('getId')->andReturn('99999999');
        $abstractUser->shouldReceive('getName')->andReturn('Anonymous Dev');
        $abstractUser->shouldReceive('getNickname')->andReturn('anon');
        $abstractUser->shouldReceive('getEmail')->andReturn(null); // No email provided
        $abstractUser->shouldReceive('getAvatar')->andReturn(null);

        $provider = Mockery::mock(GithubProvider::class);
        $provider->shouldReceive('scopes')->with(['read:user', 'user:email'])->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('github')->andReturn($provider);

        $response = $this->get(route('auth.github.callback', ['code' => 'fake-auth-code']));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('github');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['github_id' => '99999999']);
    }

    public function test_existing_email_user_is_linked_with_github_on_callback(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'developer@devpulse.local',
            'github_id' => null,
            'avatar' => null,
        ]);

        $abstractUser = Mockery::mock(SocialiteUserContract::class);
        $abstractUser->shouldReceive('getId')->andReturn('87654321');
        $abstractUser->shouldReceive('getName')->andReturn('Existing Dev');
        $abstractUser->shouldReceive('getNickname')->andReturn('existingdev');
        $abstractUser->shouldReceive('getEmail')->andReturn('developer@devpulse.local');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatars.githubusercontent.com/u/87654321');

        $provider = Mockery::mock(GithubProvider::class);
        $provider->shouldReceive('scopes')->with(['read:user', 'user:email'])->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('github')->andReturn($provider);

        $response = $this->get(route('auth.github.callback', ['code' => 'fake-code']));

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($existingUser);

        $existingUser->refresh();
        $this->assertSame('87654321', $existingUser->github_id);
        $this->assertSame('https://avatars.githubusercontent.com/u/87654321', $existingUser->avatar);
    }

    public function test_existing_github_user_is_authenticated_directly(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'veteran@devpulse.local',
            'github_id' => '555555',
            'avatar' => 'https://avatars.githubusercontent.com/u/555555',
        ]);

        $abstractUser = Mockery::mock(SocialiteUserContract::class);
        $abstractUser->shouldReceive('getId')->andReturn('555555');
        $abstractUser->shouldReceive('getName')->andReturn('Veteran Dev');
        $abstractUser->shouldReceive('getNickname')->andReturn('veterandev');
        $abstractUser->shouldReceive('getEmail')->andReturn('veteran@devpulse.local');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatars.githubusercontent.com/u/555555-new');

        $provider = Mockery::mock(GithubProvider::class);
        $provider->shouldReceive('scopes')->with(['read:user', 'user:email'])->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('github')->andReturn($provider);

        $response = $this->get(route('auth.github.callback', ['code' => 'fake-code']));

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($existingUser);

        $existingUser->refresh();
        $this->assertSame('https://avatars.githubusercontent.com/u/555555-new', $existingUser->avatar);
    }

    public function test_cancelled_github_auth_redirects_to_login_with_error(): void
    {
        $response = $this->get(route('auth.github.callback', ['error' => 'access_denied']));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('github');
        $this->assertGuest();
    }

    public function test_failing_github_api_call_redirects_to_login_with_error(): void
    {
        $provider = Mockery::mock(GithubProvider::class);
        $provider->shouldReceive('scopes')->with(['read:user', 'user:email'])->andReturnSelf();
        $provider->shouldReceive('user')->andThrow(new Exception('GitHub API rate limit exceeded'));

        Socialite::shouldReceive('driver')->with('github')->andReturn($provider);

        $response = $this->get(route('auth.github.callback', ['code' => 'bad-code']));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('github');
        $this->assertGuest();
    }
}
