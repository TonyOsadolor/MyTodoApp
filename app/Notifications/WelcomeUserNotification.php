<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $user)
    {
        //
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
            ->subject("Welcome to My ToDoApp {$this->user->first_name}")
            ->from('noreply@tinnovations.com.ng', 'My ToDoApp')
            ->markdown('mail.users.welcome', ['user' => $this->user]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => "{$this->user->full_name} Welcome to MyTodoApp",
            'body' => "In today's fast-paced digital world, staying organized is key, and MyTodoApp is here to make it effortless. \nEasily create, manage, and track your tasks while enjoying the power of automated todo alerts—never miss an important deadline again!",
            'has_button' => true,
            'url' => config('app.url') ."/dashboard",
        ];
    }
}
