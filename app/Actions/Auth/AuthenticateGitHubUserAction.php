<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\GitHubEmailMissingException;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

final readonly class AuthenticateGitHubUserAction
{
    /**
     * Authenticate, link, or create a user from a GitHub OAuth payload.
     *
     * @throws GitHubEmailMissingException
     */
    public function execute(SocialiteUserContract $githubUser): User
    {
        $githubId = (string) $githubUser->getId();
        $email = $githubUser->getEmail();
        $name = $githubUser->getName() ?? $githubUser->getNickname() ?? 'GitHub Developer';
        $avatar = $githubUser->getAvatar();

        // 1. Refuse account creation if GitHub does not provide a verified email
        if (empty($email)) {
            throw new GitHubEmailMissingException(
                'Votre compte GitHub ne partage aucune adresse e-mail publique ou vérifiée. Veuillez autoriser l\'accès à votre e-mail dans vos paramètres GitHub ou le rendre visible pour continuer.'
            );
        }

        // 2. Existing user with this github_id
        $user = User::query()->where('github_id', $githubId)->first();

        if ($user !== null) {
            if ($avatar !== null && $user->avatar !== $avatar) {
                $user->update(['avatar' => $avatar]);
            }

            return $user;
        }

        // 3. Existing user with this email: link GitHub identity
        $existingUserByEmail = User::query()->where('email', $email)->first();

        if ($existingUserByEmail !== null) {
            $existingUserByEmail->forceFill([
                'github_id' => $githubId,
                'avatar' => $avatar ?? $existingUserByEmail->avatar,
                'email_verified_at' => $existingUserByEmail->email_verified_at ?? now(),
            ])->save();

            return $existingUserByEmail;
        }

        // 4. Create new user account with null password (clean OAuth passwordless)
        $newUser = (new User)->forceFill([
            'name' => $name,
            'email' => $email,
            'github_id' => $githubId,
            'avatar' => $avatar,
            'password' => null,
            'email_verified_at' => now(),
        ]);
        $newUser->save();

        event(new Registered($newUser));

        return $newUser;
    }
}
