<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\Profile\DeleteUserAccountAction;
use App\Actions\Profile\UpdateUserProfileAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProfileActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_profile_action_updates_data_and_resets_verification_when_email_changes(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'email_verified_at' => now(),
        ]);

        $action = new UpdateUserProfileAction;
        $updatedUser = $action->execute($user, [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $this->assertSame('New Name', $updatedUser->name);
        $this->assertSame('new@example.com', $updatedUser->email);
        $this->assertNull($updatedUser->email_verified_at);
    }

    public function test_delete_user_account_action_logs_out_and_deletes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $action = new DeleteUserAccountAction;
        $action->execute($user);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
