<?php

namespace App\Http\Controllers\Auth\Styluxe;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\RoleHelper as HelpersRoleHelper;

class RegisteredUserController extends Controller
{
    public function showRegisterForm()
    {
        return view('styluxe.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        // Determine role automatically from email format
        $role = HelpersRoleHelper::determineRoleFromEmail($validated['email']);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $role,
        ]);

        Auth::login($user);

        return redirect()->route($role === 'admin' ? 'styluxe.dashboard' : 'styluxe.homepage')->with('success', '🎉 Welcome to Styluxe! Your account has been created successfully.');
    }
}
