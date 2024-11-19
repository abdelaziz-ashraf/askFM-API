<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:send-promotion-emails')->dailyAt('22:00');
Schedule::command('app:send-unanswered-questions-email')->at('22:00')->weekdays();
