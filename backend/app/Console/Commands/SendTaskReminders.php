<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-reminders {--days=1 : Days before deadline to send reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for upcoming task deadlines';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');

        $this->info("Sending task reminders for tasks due in {$days} day(s)...");

        // Get tasks that are due in the specified number of days
        $upcomingTasks = Task::with('user')
            ->whereNotNull('deadline')
            ->whereNotNull('assigned_to')
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays($days))
            ->whereNot('status', 'COMPLETED')
            ->get();

        // Also get overdue tasks
        $overdueTasks = Task::with('user')
            ->whereNotNull('deadline')
            ->whereNotNull('assigned_to')
            ->where('deadline', '<', now())
            ->whereNot('status', 'COMPLETED')
            ->get();

        $allTasks = $upcomingTasks->merge($overdueTasks);

        if ($allTasks->isEmpty()) {
            $this->info('No tasks found that need reminders.');
            return;
        }

        $sentCount = 0;
        $skippedCount = 0;

        foreach ($allTasks as $task) {
            if ($task->user && $task->user->canReceiveEmail('task_reminders')) {
                try {
                    $task->user->notify(new TaskReminderNotification($task));
                    $sentCount++;
                    $this->line("✓ Sent reminder for task '{$task->title}' to {$task->user->email}");
                } catch (\Exception $e) {
                    $this->error("✗ Failed to send reminder for task '{$task->title}': " . $e->getMessage());
                }
            } else {
                $skippedCount++;
                $reason = !$task->user ? 'no assigned user' : 'user disabled reminders';
                $this->line("- Skipped task '{$task->title}' ({$reason})");
            }
        }

        $this->info("\nSummary:");
        $this->info("- Reminders sent: {$sentCount}");
        $this->info("- Reminders skipped: {$skippedCount}");
        $this->info("- Total tasks processed: " . $allTasks->count());
    }
}
