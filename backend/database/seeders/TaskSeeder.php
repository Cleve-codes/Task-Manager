<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Enums\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users
        $admin = User::where('role', 'admin')->first();
        $users = User::where('role', 'user')->get();

        if (!$admin || $users->isEmpty()) {
            $this->command->info('Please run UserSeeder first to create users');
            return;
        }

        $tasks = [
            [
                'title' => 'Setup Development Environment',
                'description' => 'Install and configure all necessary development tools',
                'status' => TaskStatus::PENDING,
                'assigned_to' => $users->first()->id,
                'deadline' => now()->addDays(3),
            ],
            [
                'title' => 'Design Database Schema',
                'description' => 'Create ERD and design database tables',
                'status' => TaskStatus::IN_PROGRESS,
                'assigned_to' => $users->first()->id,
                'deadline' => now()->addDays(5),
            ],
            [
                'title' => 'Implement User Authentication',
                'description' => 'Build login, register, and logout functionality',
                'status' => TaskStatus::COMPLETED,
                'assigned_to' => $users->count() > 1 ? $users->get(1)->id : $users->first()->id,
                'deadline' => now()->addDays(7),
            ],
            [
                'title' => 'Create API Documentation',
                'description' => 'Document all API endpoints with examples',
                'status' => TaskStatus::PENDING,
                'assigned_to' => $users->count() > 1 ? $users->get(1)->id : $users->first()->id,
                'deadline' => now()->addDays(10),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }

        $this->command->info('Tasks seeded successfully!');
    }
}
