<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminderNotification extends Notification implements ShouldQueue
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
        // Only send if user has enabled task reminder emails
        if (!$notifiable->canReceiveEmail('task_reminders')) {
            return [];
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->task->deadline && $this->task->deadline < now()
            ? 'Overdue Task Reminder: ' . $this->task->title
            : 'Task Reminder: ' . $this->task->title;

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.task-reminder', [
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
            'type' => 'task_reminder',
            'message' => 'Reminder for task: ' . $this->task->title,
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'deadline' => $this->task->deadline?->format('Y-m-d H:i:s'),
            'user_id' => $notifiable->id,
        ];
    }
}
