<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DailyTaskReminderNotification extends Notification
{
    use Queueable;

    public ?User $user;
    public $tasks;
    public string $subject;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->tasks = $data['tasks'];
        $this->subject = $data['subject'];
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
        $todaysDate = now()->format('l, F j, Y');
        return (new MailMessage)
            ->subject($this->subject)
            ->from('noreply@tinnovations.com.ng', 'My ToDoApp')
            ->markdown("mail.todos.task_reminder_daily", 
                [
                    'day' => now()->format('l'),
                    'today' => now()->format('l, F j, Y'),
                    'user' => $this->user,
                    'tasks' => $this->tasks,
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
            'title' => "{$notifiable->full_name} Your Tasks for {$todaysDate}  are ready",
            'body' => "Hey, your Today's Tasks are ready for you, you can view them on your dashboard",
            'has_button' => true,
            'url' => '/dashboard',
        ];
    }
    
}
