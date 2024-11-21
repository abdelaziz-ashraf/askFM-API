<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:send-promotion-notification')->dailyAt('22:00');
Schedule::command('app:send-unanswered-questions-notification')->at('22:00')->weekdays();
