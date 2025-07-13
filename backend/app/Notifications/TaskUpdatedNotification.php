<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task,
        public array $changes = []
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
        // Only send if user has enabled task update emails
        if (!$notifiable->canReceiveEmail('task_updated')) {
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
            ->subject('Task Updated: ' . $this->task->title)
            ->view('emails.task-updated', [
                'user' => $notifiable,
                'task' => $this->task,
                'changes' => $this->changes
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'task_updated',
            'message' => 'Task updated: ' . $this->task->title,
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'changes' => $this->changes,
            'user_id' => $notifiable->id,
        ];
    }
}
