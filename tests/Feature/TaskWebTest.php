<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_root_redirects_to_the_task_list(): void
    {
        $this->get('/')->assertRedirect('/tasks');
    }

    public function test_the_task_list_is_displayed(): void
    {
        $task = Task::factory()->create(['title' => 'Ship the release']);

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertSee('Ship the release')
            ->assertSee($task->title);
    }

    public function test_the_task_list_can_be_filtered_by_status(): void
    {
        Task::factory()->create(['title' => 'Open item', 'status' => 'pending']);
        Task::factory()->create(['title' => 'Finished item', 'status' => 'done']);

        $this->get(route('tasks.index', ['status' => 'done']))
            ->assertOk()
            ->assertSee('Finished item')
            ->assertDontSee('Open item');
    }

    public function test_the_task_list_can_be_searched(): void
    {
        Task::factory()->create(['title' => 'Renew TLS certificate']);
        Task::factory()->create(['title' => 'Order office supplies']);

        $this->get(route('tasks.index', ['q' => 'certificate']))
            ->assertOk()
            ->assertSee('Renew TLS certificate')
            ->assertDontSee('Order office supplies');
    }

    public function test_a_task_can_be_created(): void
    {
        $response = $this->post(route('tasks.store'), [
            'title' => 'Write the docs',
            'description' => 'Cover installation and usage.',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => '2026-12-31',
        ]);

        $task = Task::where('title', 'Write the docs')->first();

        $this->assertNotNull($task);
        $this->assertSame('high', $task->priority);
        $response->assertRedirect(route('tasks.show', $task));
    }

    public function test_a_task_requires_a_title(): void
    {
        $response = $this->post(route('tasks.store'), ['title' => '']);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_a_task_rejects_an_unknown_status(): void
    {
        $this->post(route('tasks.store'), [
            'title' => 'Valid title',
            'status' => 'archived',
        ])->assertSessionHasErrors('status');
    }

    public function test_a_task_can_be_updated(): void
    {
        $task = Task::factory()->create(['title' => 'Original']);

        $this->put(route('tasks.update', $task), ['title' => 'Renamed', 'priority' => 'low'])
            ->assertRedirect(route('tasks.show', $task));

        $this->assertSame('Renamed', $task->fresh()->title);
        $this->assertSame('low', $task->fresh()->priority);
    }

    public function test_a_task_can_be_toggled_between_done_and_pending(): void
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $this->post(route('tasks.toggle', $task))->assertRedirect();
        $this->assertTrue($task->fresh()->isDone());

        $this->post(route('tasks.toggle', $task))->assertRedirect();
        $this->assertFalse($task->fresh()->isDone());
        $this->assertNull($task->fresh()->completed_at);
    }

    public function test_a_task_can_be_deleted(): void
    {
        $task = Task::factory()->create();

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_the_detail_page_shows_a_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Inspect the payload',
            'description' => 'Look at the raw JSON body.',
        ]);

        $this->get(route('tasks.show', $task))
            ->assertOk()
            ->assertSee('Inspect the payload')
            ->assertSee('Look at the raw JSON body.');
    }

    public function test_completing_a_task_stamps_the_completion_time(): void
    {
        $task = Task::factory()->create(['status' => 'pending']);
        $this->assertNull($task->completed_at);

        $task->update(['status' => 'done']);

        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_reopening_a_task_clears_the_completion_time(): void
    {
        $task = Task::factory()->create(['status' => 'done']);
        $this->assertNotNull($task->completed_at);

        $task->update(['status' => 'in_progress']);

        $this->assertNull($task->fresh()->completed_at);
    }

    public function test_overdue_detection_ignores_completed_tasks(): void
    {
        $overdue = Task::factory()->overdue()->create();
        $completed = Task::factory()->overdue()->create(['status' => 'done']);

        $this->assertTrue($overdue->isOverdue());
        $this->assertFalse($completed->isOverdue());
    }
}
