@php
    $statusOptions = [
        'pending' => 'Pending',
        'in_progress' => 'In progress',
        'done' => 'Done',
    ];

    $priorityOptions = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ];
@endphp

<div class="space-y-5">
    <x-field name="title" label="Title" :required="true"
             value="{{ $task->title ?? '' }}" placeholder="What needs doing?" />

    <x-field name="description" label="Description" type="textarea"
             value="{{ $task->description ?? '' }}" placeholder="Optional details, links, acceptance criteria…" />

    <div class="grid gap-5 sm:grid-cols-3">
        <x-field name="status" label="Status" type="select"
                 :options="$statusOptions" value="{{ $task->status ?? 'pending' }}" />

        <x-field name="priority" label="Priority" type="select"
                 :options="$priorityOptions" value="{{ $task->priority ?? 'medium' }}" />

        <x-field name="due_date" label="Due date" type="date"
                 value="{{ isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" />
    </div>
</div>
