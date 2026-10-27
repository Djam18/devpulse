<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordAction
{
    /**
     * @param  array{token: string, email: string, password: string, password_confirmation: string}  $credentials
     */
    public function execute(array $credentials): string
    {
        return Password::reset(
            $credentials,
            function (User $user) use ($credentials) {
                $user->forceFill([
                    'password' => Hash::make($credentials['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }
}
