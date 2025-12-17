<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthorizedEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Check if the email exists and determine next step.
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $email = $request->email;

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // User exists, proceed to password step
            $request->session()->put('login_email', $email);
            return redirect()->route('login');
        }

        // User doesn't exist, check if email is authorized
        if (AuthorizedEmail::isAuthorized($email)) {
            // Email is authorized, redirect to complete registration
            $request->session()->put('registration_email', $email);
            return redirect()->route('complete-registration');
        }

        // Email is not authorized
        throw ValidationException::withMessages([
            'email' => ['This email address is not authorized to access this application.'],
        ]);
    }

    /**
     * Clear the login email session.
     */
    public function clearEmail(Request $request)
    {
        $request->session()->forget('login_email');
        return redirect()->route('login');
    }
}
