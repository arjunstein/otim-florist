<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    public function __invoke(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logoutOtherDevices($request->string('current_password')->toString());

        $user->forceFill([
            'password' => Hash::make($request->string('password')->toString()),
        ])->save();

        return to_route('dashboard.settings')->with('success', 'Password updated. Other devices have been signed out.');
    }
}
