<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthenticateGitHubUserAction;
use App\Exceptions\GitHubEmailMissingException;
use App\Http\Controllers\Controller;
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
    public function callback(Request $request, AuthenticateGitHubUserAction $action): RedirectResponse
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

            $user = $action->execute($githubUser);
        } catch (GitHubEmailMissingException $e) {
            return redirect()->route('login')->withErrors([
                'github' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')->withErrors([
                'github' => 'Impossible de contacter GitHub. Veuillez réessayer.',
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
