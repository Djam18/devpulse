<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\Auth\AuthenticateGitHubUserAction;
use App\Exceptions\GitHubEmailMissingException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Mockery;
use Tests\TestCase;

final class AuthenticateGitHubUserActionTest extends TestCase
{
    use RefreshDatabase;

    private AuthenticateGitHubUserAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new AuthenticateGitHubUserAction;
    }

    public function test_throws_exception_if_email_is_missing(): void
    {
        $githubUser = Mockery::mock(SocialiteUserContract::class);
        $githubUser->shouldReceive('getId')->andReturn('12345');
        $githubUser->shouldReceive('getEmail')->andReturn(null);
        $githubUser->shouldReceive('getName')->andReturn('Ghost');
        $githubUser->shouldReceive('getNickname')->andReturn('ghost');
        $githubUser->shouldReceive('getAvatar')->andReturn(null);

        $this->expectException(GitHubEmailMissingException::class);

        $this->action->execute($githubUser);
    }

    public function test_creates_new_user_with_null_password(): void
    {
        $githubUser = Mockery::mock(SocialiteUserContract::class);
        $githubUser->shouldReceive('getId')->andReturn('987654');
        $githubUser->shouldReceive('getEmail')->andReturn('ada@example.com');
        $githubUser->shouldReceive('getName')->andReturn('Ada Lovelace');
        $githubUser->shouldReceive('getNickname')->andReturn('ada');
        $githubUser->shouldReceive('getAvatar')->andReturn('https://avatars.githubusercontent.com/u/987654');

        $user = $this->action->execute($githubUser);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Ada Lovelace', $user->name);
        $this->assertSame('ada@example.com', $user->email);
        $this->assertSame('987654', $user->github_id);
        $this->assertNull($user->password);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_links_github_to_existing_email_user(): void
    {
        $existing = User::factory()->create([
            'email' => 'grace@example.com',
            'github_id' => null,
            'avatar' => null,
        ]);

        $githubUser = Mockery::mock(SocialiteUserContract::class);
        $githubUser->shouldReceive('getId')->andReturn('112233');
        $githubUser->shouldReceive('getEmail')->andReturn('grace@example.com');
        $githubUser->shouldReceive('getName')->andReturn('Grace Hopper');
        $githubUser->shouldReceive('getNickname')->andReturn('grace');
        $githubUser->shouldReceive('getAvatar')->andReturn('https://avatars.githubusercontent.com/u/112233');

        $user = $this->action->execute($githubUser);

        $this->assertSame($existing->id, $user->id);
        $this->assertSame('112233', $user->github_id);
        $this->assertSame('https://avatars.githubusercontent.com/u/112233', $user->avatar);
    }
}
