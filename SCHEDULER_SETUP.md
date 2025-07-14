# Laravel Scheduler Setup Guide

## Overview

The event reminder system uses Laravel's built-in task scheduler to automatically send email reminders daily at 8:00 PM for events happening tomorrow.

## Current Configuration

-   **Command**: `events:send-reminders`
-   **Schedule**: Daily at 8:00 PM
-   **Timezone**: Asia/Manila (Philippines timezone)
-   **Status**: ✅ Active via Windows Task Scheduler

## Setup Instructions

### 1. For Production (Linux/Unix Server)

Add this cron job to your server:

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. For Development/Testing

#### Option A: Manual Testing

Run the scheduler manually:

```bash
php artisan schedule:run
```

#### Option B: Using Provided Scripts

-   **Windows Batch**: Double-click `run-scheduler.bat`
-   **PowerShell**: Run `.\run-scheduler.ps1`

#### Option C: Windows Task Scheduler

1. Open Windows Task Scheduler
2. Create a new task
3. Set trigger to run every minute
4. Set action to run: `php artisan schedule:run`
5. Set start in directory to your project root

### 3. Verify Setup

```bash
# List all scheduled commands
php artisan schedule:list

# Test the email command manually
php artisan events:send-reminders

# Check logs for scheduler activity
tail -f storage/logs/laravel.log
```

## Configuration Options

### Change Schedule Time

Edit `routes/console.php`:

```php
$schedule->command('events:send-reminders')
    ->dailyAt('09:00')  // Change to 9:00 AM
    ->timezone('UTC');  // Change timezone
```

### Other Schedule Options

```php
// Every hour
->hourly()

// Every day at midnight
->daily()

// Every Monday at 8:00 AM
->weeklyOn(1, '8:00')

// Only on weekdays
->weekdays()

// Only when a condition is met
->when(function () {
    return Carbon::now()->isWeekday();
})
```

## Monitoring

The command logs all activity to `storage/logs/laravel.log` including:

-   Number of events found
-   Successful email sends
-   Failed email attempts
-   Summary statistics

## Troubleshooting

### Common Issues

1. **Emails not sending**: Check your mail configuration in `.env`
2. **Scheduler not running**: Verify cron job is set up correctly
3. **Timezone issues**: Adjust timezone in the schedule configuration

### Testing

```bash
# Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('your@email.com'); });

# Check if events exist for tomorrow
php artisan tinker
>>> App\Models\Event::where('event_date', now()->addDay()->toDateString())->count();
```
