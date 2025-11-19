<?php

namespace App\Http\Middleware;

use App\Models\AuthorizedEmail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthorizedEmailOnAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (Auth::check()) {
            $user = Auth::user();

            // If user's email is not authorized or not active, log them out
            if (! AuthorizedEmail::isAuthorized($user->email)) {
                Auth::logout();

                // Check if email exists but is inactive
                $emailRecord = AuthorizedEmail::where('email', $user->email)->first();

                if ($emailRecord && ! $emailRecord->is_active) {
                    $message = 'Your account has been deactivated. Please contact an administrator.';
                } else {
                    $message = 'Your email address is no longer authorized to access this application.';
                }

                return redirect()->route('login')
                    ->withErrors(['email' => $message]);
            }
        }

        return $next($request);
    }
}
