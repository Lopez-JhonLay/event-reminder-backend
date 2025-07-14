<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the event reminder command to run daily at 8:00 PM
app()->booted(function () {
	$schedule = app(Schedule::class);

	$schedule->command('events:send-reminders')
		->dailyAt('20:00')
		->timezone('Asia/Manila') // Philippines timezone
		->description('Send daily event reminders');
});
