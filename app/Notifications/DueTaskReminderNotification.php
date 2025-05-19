<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DueTaskReminderNotification extends Notification
{
    use Queueable;

    public ?User $user;
    public ?Task $task;
    public string $subject;
    public string $startTime;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->task = $data['task'];
        $this->subject = $data['subject'];
        $this->startTime = $data['startTime'] ?? null;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->from('noreply@tinnovations.com.ng', 'My ToDoApp')
            ->markdown("mail.todos.task_reminder_due", 
                [
                    'time' => $this->startTime,
                    'day' => now()->format('l'),
                    'today' => now()->format('l, F j, Y'),
                    'user' => $this->user,
                    'task' => $this->task,
                ]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $todaysDate = now()->format('l, F j, Y');
        return [
            'title' => "{$notifiable->first_name}, {$this->task->title} starts in: {$this->startTime} minutes",
            'body' => "Hey, don't forget your task: {$this->task->title} which starts in: {$this->startTime} minutes. \n\n use the link below to view",
            'has_button' => true,
            'url' => "/tasks/{$this->task->uuid}",
        ];
    }
}
