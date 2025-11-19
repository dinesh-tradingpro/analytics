<?php

namespace App\Http\Middleware;

use App\Models\AuthorizedEmail;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthorizedEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a registration POST request
        if ($request->isMethod('POST') && $request->routeIs('register')) {
            $email = $request->input('email');

            if ($email && ! AuthorizedEmail::isAuthorized($email)) {
                return redirect()->back()
                    ->withErrors(['email' => 'This email address is not authorized to register.'])
                    ->withInput($request->except('password', 'password_confirmation'));
            }
        }

        // Check if this is a login POST request
        if ($request->isMethod('POST') && $request->routeIs('login')) {
            $email = $request->input('email');

            if ($email) {
                $emailRecord = AuthorizedEmail::where('email', $email)->first();

                if (! $emailRecord) {
                    return redirect()->back()
                        ->withErrors(['email' => 'This email address is not authorized to access this application.'])
                        ->withInput($request->only('email'));
                } elseif (! $emailRecord->is_active) {
                    return redirect()->back()
                        ->withErrors(['email' => 'Your account has been deactivated. Please contact an administrator.'])
                        ->withInput($request->only('email'));
                }
            }
        }

        return $next($request);
    }
}
