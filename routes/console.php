<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily KEMIS sync - runs every night at 11pm
Schedule::command('kemis:sync')->dailyAt('23:00');

// Daily fee arrears SMS reminders - every Monday at 7am
Schedule::command('fees:send-reminders')->weeklyOn(1, '07:00');

// Weekly backup
Schedule::command('backup:run')->weekly()->sundays()->at('01:00');
