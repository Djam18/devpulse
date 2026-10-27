<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;
use Throwable;

final class GitHubAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page with user:email scope.
     */
    public function redirect(): RedirectResponse
    {
        /** @var GithubProvider $driver */
        $driver = Socialite::driver('github');

        return $driver
            ->scopes(['read:user', 'user:email'])
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub and authenticate the user.
     */
    public function callback(Request $request): RedirectResponse
    {
        // Handle user cancellation or error from GitHub OAuth screen
        if ($request->has('error') || ! $request->has('code')) {
            return redirect()->route('login')->withErrors([
                'github' => 'La connexion avec GitHub a été annulée.',
            ]);
        }

        try {
            /** @var GithubProvider $driver */
            $driver = Socialite::driver('github');

            $githubUser = $driver
                ->scopes(['read:user', 'user:email'])
                ->user();
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')->withErrors([
                'github' => 'Impossible de contacter GitHub. Veuillez réessayer.',
            ]);
        }

        $githubId = (string) $githubUser->getId();
        $email = $githubUser->getEmail();
        $name = $githubUser->getName() ?? $githubUser->getNickname() ?? 'GitHub Developer';
        $avatar = $githubUser->getAvatar();

        // 1. Refuse account creation if GitHub does not provide a verified email
        if (empty($email)) {
            return redirect()->route('login')->withErrors([
                'github' => 'Votre compte GitHub ne partage aucune adresse e-mail vérifiée. Veuillez autoriser l\'accès à votre e-mail pour créer votre compte.',
            ]);
        }

        // 2. Look for existing user with this github_id
        $user = User::query()->where('github_id', $githubId)->first();

        if ($user) {
            if ($avatar && $user->avatar !== $avatar) {
                $user->update(['avatar' => $avatar]);
            }

            Auth::login($user, remember: true);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }

        // 3. If email exists, link GitHub ID to that existing account
        $existingUser = User::query()->where('email', $email)->first();

        if ($existingUser) {
            $existingUser->forceFill([
                'github_id' => $githubId,
                'avatar' => $avatar ?? $existingUser->avatar,
                'email_verified_at' => $existingUser->email_verified_at ?? now(),
            ])->save();

            Auth::login($existingUser, remember: true);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }

        // 4. Create new user account with null password (clean OAuth passwordless)
        $newUser = User::query()->create([
            'name' => $name,
            'email' => $email,
            'github_id' => $githubId,
            'avatar' => $avatar,
            'password' => null,
            'email_verified_at' => now(),
        ]);

        event(new Registered($newUser));

        Auth::login($newUser, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
