<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskReminder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class TaskService
{
    /**
     * Send Today's Task Notification
     */
    public function sendTodaysTasks()
    {
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        Task::active()->whereBetween('start_date', [$todayStart, $todayEnd])
            ->chunkById(200, function (Collection $tasks) {
                $tasks->each->update(['departed' => false]);
            }, column: 'id');

        // $tasks = Task::active()->whereBetween('start_date', [$todayStart, $todayEnd])->get();
    }

    /**
     * Set Task Reminders
     */
    public function setReminders(User $user, Task $task): void
    {
        if (!$user || !$task) {
            Log::error('Model not found: ' . ($user ?: $task));
            return;
        }

        $currentTime = now();
        $reminderTimes = [30, 15, 0];

        foreach ($reminderTimes as $time) {
            $reminderTime = $task->start_date->subMinutes($time);

            if ($reminderTime->greaterThan($currentTime)) {
                TaskReminder::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'task_id' => $task->id,
                        'reminder_time' => $task->start_date->subMinutes($time),
                    ],
                );
            }
        }
    }
}
