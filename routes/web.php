<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskPageController;
use App\Mail\LongRunningTimerMail;
use App\Mail\WeeklySummaryMail;
use App\Mail\WelcomeUserMail;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tasks', [TaskPageController::class, 'index'])->name('tasks.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Local Development Email Previews for Testers & Reviewers
if (app()->environment('local')) {
    Route::prefix('mail-preview')->group(function () {
        Route::get('/welcome', function () {
            $user = User::first() ?? new User(['name' => 'Alexandre Dev', 'email' => 'alex@devpulse.io']);

            return (new WelcomeUserMail($user))->render();
        });

        Route::get('/timer', function () {
            $user = User::first() ?? new User(['name' => 'Alexandre Dev', 'email' => 'alex@devpulse.io']);
            $entry = TimeEntry::first() ?? new TimeEntry([
                'user_id' => $user->id ?? 1,
                'started_at' => now()->subHours(6)->subMinutes(15),
                'description' => 'Optimisation du pipeline Docker & Mailpit',
            ]);

            return (new LongRunningTimerMail($user, $entry))->render();
        });

        Route::get('/weekly', function () {
            $user = User::first() ?? new User(['name' => 'Alexandre Dev', 'email' => 'alex@devpulse.io']);

            return (new WeeklySummaryMail(
                user: $user,
                totalHours: 38.5,
                pomodorosCount: 18,
                completedTasksCount: 9,
                topProjects: [
                    ['name' => 'API v2 Microservices', 'color' => '#7D2D68', 'hours' => 22.0],
                    ['name' => 'Design System Plum & Peach', 'color' => '#E63946', 'hours' => 16.5],
                ]
            ))->render();
        });
    });
}

require __DIR__.'/auth.php';
