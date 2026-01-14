<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule sync commands to run every night at midnight
Schedule::command('sync:users --force')
    ->dailyAt('00:00')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping()
    ->runInBackground()
    ->onFailure(fn () => logger('User sync command failed'))
    ->onSuccess(fn () => logger('User sync command completed successfully'));

Schedule::command('sync:transactions --force')
    ->dailyAt('00:00')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping()
    ->runInBackground()
    ->onFailure(fn () => logger('Transactions sync command failed'))
    ->onSuccess(fn () => logger('Transactions sync command completed successfully'));

Schedule::command('sync:tickets --force')
    ->dailyAt('00:00')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping()
    ->runInBackground()
    ->onFailure(fn () => logger('Tickets sync command failed'))
    ->onSuccess(fn () => logger('Tickets sync command completed successfully'));
