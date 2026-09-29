<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_a_paginated_task_collection(): void
    {
        Task::factory(3)->create();

        $this->getJson(route('api.tasks.index'))
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'title', 'description', 'status', 'priority',
                        'due_date', 'is_overdue', 'completed_at',
                        'created_at', 'updated_at', 'links' => ['self'],
                    ],
                ],
                'links', 'meta',
            ]);
    }

    public function test_it_filters_the_collection_by_status(): void
    {
        Task::factory()->create(['title' => 'Open', 'status' => 'pending']);
        Task::factory()->create(['title' => 'Closed', 'status' => 'done']);

        $this->getJson(route('api.tasks.index', ['status' => 'done']))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Closed');
    }

    public function test_it_creates_a_task(): void
    {
        $response = $this->postJson(route('api.tasks.store'), [
            'title' => 'Posted over JSON',
            'priority' => 'high',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Posted over JSON')
            ->assertJsonPath('data.priority', 'high')
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('tasks', ['title' => 'Posted over JSON']);
    }

    public function test_it_returns_validation_errors_for_a_blank_title(): void
    {
        $this->postJson(route('api.tasks.store'), ['title' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_returns_validation_errors_for_an_invalid_priority(): void
    {
        $this->postJson(route('api.tasks.store'), [
            'title' => 'Valid title',
            'priority' => 'urgent',
        ])->assertStatus(422)->assertJsonValidationErrors(['priority']);
    }

    public function test_it_shows_a_single_task(): void
    {
        $task = Task::factory()->create();

        $this->getJson(route('api.tasks.show', $task))
            ->assertOk()
            ->assertJsonPath('data.id', $task->id);
    }

    public function test_it_updates_a_task(): void
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $this->patchJson(route('api.tasks.update', $task), ['status' => 'done'])
            ->assertOk()
            ->assertJsonPath('data.status', 'done');

        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_it_deletes_a_task(): void
    {
        $task = Task::factory()->create();

        $this->deleteJson(route('api.tasks.destroy', $task))->assertOk();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_it_returns_404_for_a_missing_task(): void
    {
        $this->getJson('/api/tasks/999999')->assertNotFound();
    }

    public function test_it_exposes_summary_statistics(): void
    {
        Task::factory()->create(['status' => 'pending', 'due_date' => now()->addWeek()->toDateString()]);
        Task::factory()->create(['status' => 'pending', 'due_date' => null]);
        Task::factory()->create(['status' => 'done', 'due_date' => now()->subWeek()->toDateString()]);
        Task::factory()->overdue()->create();

        $this->getJson(route('api.tasks.stats'))
            ->assertOk()
            ->assertJsonPath('total', 4)
            ->assertJsonPath('pending', 3)
            ->assertJsonPath('done', 1)
            ->assertJsonPath('overdue', 1);
    }

    public function test_it_caps_the_page_size(): void
    {
        Task::factory(5)->create();

        $this->getJson(route('api.tasks.index', ['per_page' => 500]))
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100);
    }
}
