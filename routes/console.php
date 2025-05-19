<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;
use App\Enums\TaskReminderJobTypeEnum;
use Illuminate\Foundation\Inspiring;
use App\Jobs\TaskReminderJob;
use App\Models\TaskReminder;
use App\Models\Task;
use Carbon\Carbon;

/* Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote'); */

/**
 * Schedule Daily Reminder
 */
Schedule::call(function () {
    $todayStart = Carbon::now()->startOfDay();
    $todayEnd = Carbon::now()->endOfDay();
    $allTasks = Task::active()->whereBetween('start_date', [$todayStart, $todayEnd])
        ->get()->groupBy('user_id');
    
    if ($allTasks->isEmpty()) {return;}

    foreach ($allTasks as $userId => $tasks) {
        TaskReminderJob::dispatch($userId, $tasks, TaskReminderJobTypeEnum::DAILY);        
    }
})->timezone('Africa/Lagos')->dailyAt('06:00');

/**
 * Send Minute Reminder
 */
Schedule::call(function () {

    $reminders = TaskReminder::where('sent', false)
        ->whereBetween('reminder_time', [Carbon::now(), Carbon::now()->addMinutes(30)])
        ->get();

    if ($reminders->isEmpty()) {
        return;
    }

    $reminders->each(function ($reminder) {
        $task = Task::where('id', $reminder->task_id)->where('notify_me', true)->first();

        if (!$task) {
            return;
        }

        TaskReminderJob::dispatch($reminder->user_id, [$task], TaskReminderJobTypeEnum::DUE);

        TaskReminder::where([
            'user_id' => $reminder->user_id,
            'task_id' => $reminder->task_id,
            'reminder_time' => $reminder->reminder_time,
        ])->update(['sent' => true]);

    });
})->everyMinute();

/**
 * Run to Send All Pending Jobs
 */
Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()->withoutOverlapping()->runInBackground();
