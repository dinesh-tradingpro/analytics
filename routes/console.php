<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the analytics sync command
Schedule::command('analytics:sync')
    ->hourly() // Run every hour
    ->withoutOverlapping() // Prevent multiple instances
    ->runInBackground() // Run in background
    ->onFailure(function () {
        // Log failure or send notification
        logger('Analytics sync command failed');
    })
    ->onSuccess(function () {
        // Log success
        logger('Analytics sync command completed successfully');
    });
