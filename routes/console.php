<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:send-promotion-emails')->dailyAt('22:00');
Schedule::command('app:send-unanswered-questions-email')->at('22:00')->everyTwoDays();
