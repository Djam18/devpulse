<?php

namespace App\Http\Controllers;

use App\Actions\Profile\DeleteUserAccountAction;
use App\Actions\Profile\UpdateUserProfileAction;
use App\Http\Requests\ProfileDestroyRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, UpdateUserProfileAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated());

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(ProfileDestroyRequest $request, DeleteUserAccountAction $action): RedirectResponse
    {
        $action->execute($request->user());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
