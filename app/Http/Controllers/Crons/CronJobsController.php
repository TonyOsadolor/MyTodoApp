<?php

namespace App\Http\Controllers\Crons;

use App\Enums\TaskReminderJobTypeEnum;
use App\Http\Controllers\Controller;
use App\Jobs\TaskReminderJob;
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class CronJobsController extends Controller
{
    /**
     * Send Today's Task Notification
     */
    public function sendTodaysTasks()
    {
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();
        $allTasks = Task::active()->whereBetween('start_date', [$todayStart, $todayEnd])
            ->get()->groupBy('user_id');

        if(!$allTasks){return;}

        foreach ($allTasks as $userId => $tasks) {
            TaskReminderJob::dispatch($userId, $tasks, TaskReminderJobTypeEnum::DAILY);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tasks Reminder Successful',
        ], 200);
    }

    /**
     * Check hourly Task
     */
    public function scheduleHourly()
    {
        $task = Task::find(13);
        TaskReminderJob::dispatch($task->user_id, [$task], TaskReminderJobTypeEnum::DUE);

        return response()->json([
            'status' => true,
            'message' => 'Tasks Reminder Successful',
        ], 200);
    }
}
