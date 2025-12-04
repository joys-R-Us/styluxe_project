<?php

namespace App\Http\Controllers\Auth\Styluxe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\RoleHelper as HelpersRoleHelper;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('styluxe.login');
    }

    public function login(Request $request)
    {
        // Validate login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }
 
        $request->session()->regenerate();

        $currentUser = Auth::user();

        // Ensure we operate on the actual Eloquent user model so save() is available.
        $userModel = \App\Models\User::find($currentUser->getAuthIdentifier());

        if ($userModel) {
            $userModel->role = HelpersRoleHelper::determineRoleFromEmail($currentUser->email);
            $userModel->save();
            $user = $userModel;
        } else {
            // Fallback: set role on the current user object without persisting
            $currentUser->role = HelpersRoleHelper::determineRoleFromEmail($currentUser->email);
            $user = $currentUser;
        }

        return redirect()->route($user->role === 'client' ? 'styluxe.homepage' : 'styluxe.dashboard');
    }

    /* private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
            case 'manager':
            case 'staff':
            case 'supplier':
                return redirect()->route('styluxe.dashboard');

            case 'client':
            default:
                return redirect()->route('styluxe.homepage');
        }
    } */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('styluxe.homepage');
    }
}
