<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the user sync command
Schedule::command('sync:users')
    ->daily() // Run every day
    ->withoutOverlapping() // Prevent multiple instances
    ->runInBackground() // Run in background
    ->onFailure(function () {
        // Log failure or send notification
        logger('User sync command failed');
    })
    ->onSuccess(function () {
        // Log success
        logger('User sync command completed successfully');
    });
