<?php

use App\Http\Controllers\MarketingController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'check.authorized.access'])
    ->name('dashboard');

Route::view('user-analytics', 'user-analytics')
    ->middleware(['auth', 'verified', 'check.authorized.access'])
    ->name('user-analytics');

Route::view('transactions', 'transactions')
    ->middleware(['auth', 'verified', 'check.authorized.access'])
    ->name('transactions');

Route::view('transaction-insights', 'transaction-insights')
    ->middleware(['auth', 'verified', 'check.authorized.access'])
    ->name('transaction-insights');

Route::middleware(['auth', 'check.authorized.access'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/authorized-emails', 'settings.authorized-emails')
        ->middleware('can:manage-authorized-emails')
        ->name('authorized-emails.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Dashboard API routes
    Route::prefix('api/dashboard')->name('dashboard.')->group(function () {
        Route::get('new-users', [MarketingController::class, 'getNewUsersData'])->name('new-users');
        Route::get('active-users', [MarketingController::class, 'getActiveUsersData'])->name('active-users');
        Route::get('inactive-users', [MarketingController::class, 'getInactiveUsersData'])->name('inactive-users');
        Route::get('all-data', [MarketingController::class, 'getDashboardData'])->name('all-data');

        // Test endpoint
        Route::get('test', function () {
            $controller = new MarketingController;
            $response = $controller->getDashboardData();
            $data = json_decode($response->getContent(), true);

            return response()->json([
                'success' => true,
                'raw_data' => $data,
                'summary' => [
                    'new_count' => count($data['data']['new_users']['chart_data'] ?? []),
                    'active_count' => count($data['data']['active_users']['chart_data'] ?? []),
                    'inactive_count' => count($data['data']['inactive_users']['chart_data'] ?? []),
                ],
            ]);
        })->name('test');
    });
});
