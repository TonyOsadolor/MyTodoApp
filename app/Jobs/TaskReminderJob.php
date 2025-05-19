<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Task;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\EbulksmsService;
use App\Enums\TaskReminderJobTypeEnum;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\DueTaskReminderNotification;
use App\Notifications\DailyTaskReminderNotification;

class TaskReminderJob implements ShouldQueue
{
    use Queueable;
    
    protected $tasks;
    protected $userId;
    protected User $user;
    protected string $reminderType;
    protected $todaysDate;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $tasks, $reminderType)
    {
        $this->user = User::find($userId);
        $this->tasks = $tasks;
        $this->reminderType = $reminderType;
        $this->todaysDate = now()->format('l, F j, Y');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->reminderType == TaskReminderJobTypeEnum::DAILY) {
            $data = [
                'tasks' => $this->tasks,
                'subject' => "{$this->user->first_name} Here are your ToDo for today ['{$this->todaysDate}']",
            ];

            $this->sendSms($data, 'daily');
            $this->sendWhatsApp($data, 'daily');
            $this->user->notify(new DailyTaskReminderNotification($this->user, $data));
        }

        if ($this->reminderType == TaskReminderJobTypeEnum::DUE) {
            $task = Task::find($this->tasks[0]->id);
            if(!$task || !$task->notify_me){return;}
            
            $startTime = ceil(Carbon::now()->diffInMinutes(Carbon::parse($task->start_date)));
            $data = [
                'task' => $task,
                'subject' => "{$this->user->first_name}  your Task: {$task->title} starts in {$startTime} minutes",
                'startTime' => $startTime,
            ];
            
            $this->sendSms($data, 'due');
            $this->sendWhatsApp($data, 'due');
            $this->user->notify(new DueTaskReminderNotification($this->user, $data));
        }
        
    }

    /**
     * Send SMS Notification for Bulk Tasks
     */
    private function sendSms(mixed $data, string $type)
    {
        $ebulkSms = app()->make(EbulksmsService::class);
        $userId = $this->user->id ?? $this->user['id'];
        $subscriptionPlan = SubscriptionPlan::active()->where('code', 'SMS7814')->first();
        if($subscriptionPlan){
            $subscription = Subscription::active()->where('user_id', $userId)
                ->where('subscription_plan_id', $subscriptionPlan->id)->first();
        }

        if($subscription) {
            $data = [
                'text_number' => $this->user->text_number,
                'messagetext' => $this->getText($data, $type),
            ];
            $ebulkSms->sendSms($data);
            return;
        }
        return;
    }

    /**
     * Send SMS Notification for Bulk Tasks
     */
    private function sendWhatsApp(mixed $data, string $type)
    {
        $ebulkSms = app()->make(EbulksmsService::class);
        $userId = $this->user->id ?? $this->user['id'];
        $subscriptionPlan = SubscriptionPlan::active()->where('code', 'WHT5763')->first();
        if($subscriptionPlan){
            $subscription = Subscription::active()->where('user_id', $userId)
                ->where('subscription_plan_id', $subscriptionPlan->id)->first();
        }

        if($subscription) {
            $data = [
                'subject' => 'Task Reminder',
                'text_number' => $this->user->text_number,
                'messagetext' => $this->getWhatsAppMsg($data, $type),
            ];
            $ebulkSms->sendWhatsapp($data);
            return;
        }
        return;
    }

    /**
     * Format WhatsApp Message
     */
    private function getWhatsAppMsg($data, $type)
    {
        $theTasks = !empty($data['tasks']) && is_countable($data['tasks']) ? count($data['tasks']) : 1;
        $taskNumber = $theTasks > 1 ? $theTasks .'Tasks' : $theTasks. 'Task';
        $startTime = $data['startTime'] ?? null;
        $singlTask = isset($data['task']) ? $data['task']->title : null;
        $taskUuid = isset($data['task']) ? $data['task']->uuid : null;
        $appUrl = config('app.url');

        if($type == 'daily'){ 
            $heading = "*{$this->user->full_name}* Your Todo for today is ready and you have a total of: {$taskNumber} today {$this->todaysDate} \n\n";
            $sn = 1;
            $taskList = '';

            foreach ($this->tasks as $task) {
                $taskList .= "{$sn}. {$task->title} - Starts by: {$task->start_date->format('g:i A')}";
                if (!empty($task->due_date)) {
                    $taskList .= " | Ends by: {$task->due_date->format('g:i A')}";
                }
                $taskList .= "\n";
                $sn++;
            }

            $endUrl = "/dashboard";
            $closing = "\n\nDo have a lovely day";

            return $heading . $taskList . "\n\n" . $endUrl . $closing;
        }

        if($type == 'due'){ 
            $heading = "*{$this->user->full_name}* \nThis task {$singlTask} will be due in: {$startTime} minutes.\n\n";

            $endUrl = "{$appUrl}/task/{$taskUuid}";
            $closing = "\n\nDo have a lovely day";

            return $heading .$endUrl .$closing;
        }
    }

    /**
     * Get Text for SMS
     */
    private function getText($data, $type)
    {
        $msg = '';
        $theTasks = !empty($data['tasks']) && is_countable($data['tasks']) ? count($data['tasks']) : 1;
        $taskNumber = $theTasks > 1 ? $theTasks .'Tasks' : $theTasks. 'Task';
        $startTime = $data['startTime'] ?? null;
        $singlTask = isset($data['task']) ? $data['task']->title : null;

        if($type == 'daily'){
            $msg = "Hey {$this->user->first_name} you have {$taskNumber} today, and they are ready to be viewed on your Dashboard. \nBest Wishes today Champ!";
        }
        
        if($type == 'due'){
            $msg = "Hey {$this->user->first_name} you've got one Task coming up in {$startTime} minutes. \nTask Name: {$singlTask}. \nBest Wishes Champ!";
        }
        return $msg;
    }
}
