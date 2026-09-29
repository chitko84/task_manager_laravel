<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->boolean(70) ? fake()->paragraph() : null,
            'status' => fake()->randomElement(Task::STATUSES),
            'priority' => fake()->randomElement(Task::PRIORITIES),
            'due_date' => fake()->boolean(60)
                ? fake()->dateTimeBetween('-2 weeks', '+3 weeks')->format('Y-m-d')
                : null,
        ];
    }

    public function overdue(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => now()->subDays(3)->toDateString(),
        ]);
    }
}
