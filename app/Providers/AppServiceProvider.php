<?php

namespace App\Providers;

use App\Models\AuthorizedEmail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Custom login response to check authorized emails
        $this->app->instance(LoginResponse::class, new class implements LoginResponse
        {
            public function toResponse($request)
            {
                // Check if the authenticated user's email is authorized and active
                if (auth()->check()) {
                    $userEmail = auth()->user()->email;

                    if (! AuthorizedEmail::isAuthorized($userEmail)) {
                        auth()->guard()->logout();

                        // Check if email exists but is inactive
                        $emailExists = AuthorizedEmail::where('email', $userEmail)->exists();
                        $message = $emailExists
                            ? 'Your account has been deactivated. Please contact an administrator.'
                            : 'Your email address is not authorized to access this application.';

                        return redirect()->route('login')
                            ->withErrors(['email' => $message]);
                    }
                }

                return redirect()->intended(route('dashboard'));
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sync admin emails from config to database automatically
        $this->syncAdminEmailsFromConfig();

        // Define gate for managing authorized emails
        Gate::define('manage-authorized-emails', function ($user) {
            $adminEmails = config('authorized_emails.admin_emails', []);

            return in_array($user->email, $adminEmails);
        });
    }

    /**
     * Sync admin emails from config to authorized_emails table
     */
    protected function syncAdminEmailsFromConfig(): void
    {
        try {
            $adminEmails = config('authorized_emails.admin_emails', []);

            foreach ($adminEmails as $email) {
                AuthorizedEmail::updateOrCreate(
                    ['email' => $email],
                    [
                        'added_by_email' => 'system',
                        'notes' => 'Admin email synced from config',
                        'is_active' => true,
                    ]
                );
            }
        } catch (\Exception $e) {
            // Silently fail during migrations or when DB is not ready
            // This prevents issues during fresh installations
        }
    }
}
