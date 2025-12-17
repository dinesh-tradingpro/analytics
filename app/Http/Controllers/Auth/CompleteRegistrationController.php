<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Models\AuthorizedEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompleteRegistrationController extends Controller
{
    /**
     * Show the complete registration form.
     */
    public function show()
    {
        // Check if registration email is in session
        if (!session()->has('registration_email')) {
            return redirect()->route('login');
        }

        return view('livewire.auth.complete-registration');
    }

    /**
     * Handle the complete registration request.
     */
    public function store(Request $request, CreateNewUser $creator)
    {
        // Validate the request
        $validated = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        // Verify email matches session
        if ($validated['email'] !== session('registration_email')) {
            throw ValidationException::withMessages([
                'email' => ['Invalid registration session.'],
            ]);
        }

        // Verify email is authorized
        if (!AuthorizedEmail::isAuthorized($validated['email'])) {
            throw ValidationException::withMessages([
                'email' => ['This email is not authorized to register.'],
            ]);
        }

        // Check if user already exists
        if (User::where('email', $validated['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => ['An account with this email already exists.'],
            ]);
        }

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(), // Auto-verify since email is authorized
        ]);

        // Log the user in
        Auth::login($user);

        // Clear the registration session
        session()->forget('registration_email');

        // Redirect to dashboard
        return redirect()->intended(route('dashboard'));
    }
}
