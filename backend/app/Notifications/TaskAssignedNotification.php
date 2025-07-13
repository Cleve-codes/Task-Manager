<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Only send if user has enabled task assignment emails
        if (!$notifiable->canReceiveEmail('task_assigned')) {
            return [];
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->view('emails.task-assigned', [
                'user' => $notifiable,
                'task' => $this->task
            ])
            ->withSymfonyMessage(function ($message) {
                $message->getHeaders()
                    ->addTextHeader('X-Mailer', 'Task Management System')
                    ->addTextHeader('X-Priority', '3')
                    ->addTextHeader('X-MSMail-Priority', 'Normal')
                    ->addTextHeader('List-Unsubscribe', '<' . config('app.url') . '/unsubscribe>')
                    ->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply');
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'task_assigned',
            'message' => 'You have been assigned a new task: ' . $this->task->title,
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'user_id' => $notifiable->id,
        ];
    }
}
