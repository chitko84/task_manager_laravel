<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $curated = [
            [
                'title' => 'Draft the Q3 roadmap',
                'description' => 'Collect input from each team lead and turn it into a one-page roadmap.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addDays(2)->toDateString(),
            ],
            [
                'title' => 'Renew the TLS certificate',
                'description' => 'Wildcard cert for *.example.test expires soon. Renew and redeploy.',
                'status' => 'pending',
                'priority' => 'high',
                'due_date' => now()->subDay()->toDateString(),
            ],
            [
                'title' => 'Write onboarding guide',
                'description' => 'Setup steps, coding conventions, and how to run the test suite.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => now()->addWeek()->toDateString(),
            ],
            [
                'title' => 'Audit unused composer packages',
                'description' => null,
                'status' => 'pending',
                'priority' => 'low',
                'due_date' => null,
            ],
            [
                'title' => 'Fix flaky checkout test',
                'description' => 'Fails roughly one run in ten when the queue driver is sync.',
                'status' => 'done',
                'priority' => 'medium',
                'due_date' => now()->subDays(4)->toDateString(),
            ],
            [
                'title' => 'Rotate database credentials',
                'description' => 'Done as part of the quarterly security review.',
                'status' => 'done',
                'priority' => 'high',
                'due_date' => now()->subWeek()->toDateString(),
            ],
        ];

        foreach ($curated as $attributes) {
            Task::create($attributes);
        }

        Task::factory(14)->create();
    }
}
