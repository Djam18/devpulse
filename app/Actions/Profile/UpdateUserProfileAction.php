<?php

namespace App\Actions\Profile;

use App\Models\User;

class UpdateUserProfileAction
{
    /**
     * @param  array{name: string, email: string}  $data
     */
    public function execute(User $user, array $data): User
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }
}
