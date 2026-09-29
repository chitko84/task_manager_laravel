<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $filters = [
            'q' => request('q'),
            'status' => request('status'),
            'priority' => request('priority'),
        ];

        $tasks = Task::query()
            ->search($filters['q'])
            ->ofStatus($filters['status'])
            ->when(
                in_array($filters['priority'], Task::PRIORITIES, true),
                fn ($query) => $query->where('priority', $filters['priority'])
            )
            ->defaultOrder()
            ->paginate(10)
            ->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'filters' => $filters,
            'stats' => Task::stats(),
        ]);
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = Task::create($request->validated());

        return redirect()
            ->route('tasks.show', $task)
            ->with('status', "Task “{$task->title}” created.");
    }

    public function show(Task $task): View
    {
        return view('tasks.show', ['task' => $task]);
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()
            ->route('tasks.show', $task)
            ->with('status', "Task “{$task->title}” updated.");
    }

    public function toggle(Task $task): RedirectResponse
    {
        $task->update(['status' => $task->isDone() ? 'pending' : 'done']);

        return back()->with(
            'status',
            $task->isDone()
                ? "Task “{$task->title}” completed."
                : "Task “{$task->title}” reopened."
        );
    }

    public function destroy(Task $task): RedirectResponse
    {
        $title = $task->title;
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('status', "Task “{$title}” deleted.");
    }
}
