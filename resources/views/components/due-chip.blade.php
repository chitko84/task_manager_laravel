@props(['task'])

@php
    if ($task->isDone()) {
        $classes = 'bg-zinc-100 text-zinc-500 ring-zinc-500/20';
        $label = 'Completed '.($task->completed_at ?? $task->updated_at)->format('M j');
    } elseif ($task->isOverdue()) {
        $classes = 'bg-rose-50 text-rose-700 ring-rose-600/20';
        $label = 'Overdue · '.$task->due_date->format('M j');
    } elseif ($task->due_date->lte(now()->addDays(3))) {
        $classes = 'bg-amber-50 text-amber-800 ring-amber-600/20';
        $label = 'Due soon · '.$task->due_date->format('M j');
    } else {
        $classes = 'bg-zinc-50 text-zinc-600 ring-zinc-500/20';
        $label = 'Due '.$task->due_date->format('M j');
    }
@endphp

<span class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $classes }}">
    <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
    </svg>
    {{ $label }}
</span>
