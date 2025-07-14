<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Mail\EventReminderMail;
use App\Models\Event;

class SendEventReminders extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'events:send-reminders';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send email reminders for events happening tomorrow';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$tomorrow = Carbon::tomorrow()->toDateString();

		$events = Event::with('user')
			->where('event_date', $tomorrow)
			->get();

		$count = 0;
		$errors = 0;

		$this->info("Found {$events->count()} events scheduled for tomorrow ({$tomorrow})");

		foreach ($events as $event) {
			try {
				Mail::to($event->user->email)->send(new EventReminderMail($event));
				$count++;
				$this->line("✓ Sent reminder to {$event->user->email} for event: {$event->title}");
			} catch (\Exception $e) {
				$errors++;
				$this->error("✗ Failed to send reminder to {$event->user->email} for event: {$event->title}");
				Log::error("Failed to send event reminder", [
					'event_id' => $event->id,
					'user_email' => $event->user->email,
					'error' => $e->getMessage()
				]);
			}
		}

		$this->info("Event reminders process completed:");
		$this->info("- Successfully sent: {$count}");
		if ($errors > 0) {
			$this->warn("- Failed to send: {$errors}");
		}

		// Log the summary
		Log::info("Event reminders sent", [
			'date' => $tomorrow,
			'total_events' => $events->count(),
			'successful' => $count,
			'failed' => $errors
		]);
	}
}
