<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\Auth\RegisterUserAction;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class RegisterUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_registers_user_hashes_password_and_fires_event(): void
    {
        Event::fake([Registered::class]);

        $action = new RegisterUserAction;
        $user = $action->execute([
            'name' => 'Marie Curie',
            'email' => 'marie@example.com',
            'password' => 'secret1234',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Marie Curie', $user->name);
        $this->assertSame('marie@example.com', $user->email);
        $this->assertTrue(Hash::check('secret1234', $user->password));

        Event::assertDispatched(Registered::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });

        $this->assertAuthenticatedAs($user);
    }
}
