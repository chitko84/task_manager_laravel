<x-layouts.app title="Tasks">
    <div class="mb-8">
        <p class="text-sm font-semibold text-indigo-600">Workspace</p>
        <div class="mt-1 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Tasks</h1>
                <p class="mt-1.5 text-sm text-zinc-500">
                    {{ $stats['total'] }} total ·
                    <span class="font-medium text-zinc-700">{{ $stats['pending'] + $stats['in_progress'] }} open</span> ·
                    <span class="font-medium {{ $stats['overdue'] > 0 ? 'text-rose-600' : 'text-zinc-700' }}">{{ $stats['overdue'] }} overdue</span>
                </p>
            </div>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-stat-card label="Pending" :count="$stats['pending']" accent="bg-amber-400">
            <x-slot:icon>
                <svg class="size-4.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="10" cy="10" r="7.25" />
                    <path d="M10 6.5V10l2.5 1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="In progress" :count="$stats['in_progress']" accent="bg-sky-400">
            <x-slot:icon>
                <svg class="size-4.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M10 2.75a7.25 7.25 0 1 1-7.25 7.25" stroke-linecap="round" />
                    <path d="M10 6.5V10l2.5 1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="Done" :count="$stats['done']" accent="bg-emerald-400">
            <x-slot:icon>
                <svg class="size-4.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="10" cy="10" r="7.25" />
                    <path d="m7 10.2 2.1 2.1L13.2 8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="Overdue" :count="$stats['overdue']" accent="bg-rose-500">
            <x-slot:icon>
                <svg class="size-4.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M10 3.5 17 15.5H3L10 3.5Z" stroke-linejoin="round" />
                    <path d="M10 8.5v3M10 13.6v.01" stroke-linecap="round" />
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}"
          class="sticky top-20 z-30 mb-6 flex flex-col gap-3 rounded-2xl border border-zinc-200/80 bg-white/85 p-4 shadow-sm backdrop-blur-md sm:flex-row sm:items-center">
        <div class="relative sm:flex-1">
            <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-zinc-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
            </svg>
            <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search title or description…"
                   class="w-full rounded-xl border border-zinc-200 bg-zinc-50/60 py-2 pl-9 pr-3 text-sm shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <select name="status" onchange="this.form.submit()" aria-label="Filter by status"
                    class="rounded-xl border border-zinc-200 bg-zinc-50/60 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 sm:w-36">
                <option value="">All statuses</option>
                @foreach ([['pending', 'Pending'], ['in_progress', 'In progress'], ['done', 'Done']] as [$value, $label])
                    <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="priority" onchange="this.form.submit()" aria-label="Filter by priority"
                    class="rounded-xl border border-zinc-200 bg-zinc-50/60 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 sm:w-32">
                <option value="">All priorities</option>
                @foreach ([['high', 'High'], ['medium', 'Medium'], ['low', 'Low']] as [$value, $label])
                    <option value="{{ $value }}" @selected($filters['priority'] === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit"
                    class="rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-700">
                Apply
            </button>

            @if (array_filter($filters))
                <a href="{{ route('tasks.index') }}"
                   class="text-sm font-medium text-zinc-500 underline-offset-4 transition hover:text-zinc-900 hover:underline">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-zinc-200/80 bg-white shadow-sm">
        @forelse ($tasks as $task)
            <div class="group flex items-start gap-4 border-b border-zinc-100 px-5 py-4 transition last:border-0 hover:bg-zinc-50/80 sm:px-6">
                <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="mt-0.5 shrink-0">
                    @csrf
                    <button type="submit"
                            title="{{ $task->isDone() ? 'Mark as pending' : 'Mark as done' }}"
                            aria-label="{{ $task->isDone() ? 'Mark as pending' : 'Mark as done' }}"
                            class="grid size-6 place-items-center rounded-full border-2 transition {{ $task->isDone() ? 'border-emerald-500 bg-emerald-500 text-white hover:bg-emerald-400' : 'border-zinc-300 text-transparent hover:border-emerald-500 hover:text-emerald-500' }}">
                        <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1.5">
                        <a href="{{ route('tasks.show', $task) }}"
                           class="truncate font-medium text-zinc-900 transition hover:text-indigo-600 {{ $task->isDone() ? 'text-zinc-400 line-through decoration-zinc-300' : '' }}">
                            {{ $task->title }}
                        </a>
                        <x-priority-badge :priority="$task->priority" />
                    </div>

                    @if ($task->description)
                        <p class="mt-1 line-clamp-1 text-sm text-zinc-500">{{ $task->description }}</p>
                    @endif

                    <div class="mt-2.5 flex flex-wrap items-center gap-2">
                        <x-status-badge :status="$task->status" />
                        @if ($task->due_date)
                            <x-due-chip :task="$task" />
                        @endif
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-1 opacity-100 transition sm:opacity-0 sm:group-hover:opacity-100 sm:group-focus-within:opacity-100">
                    <a href="{{ route('tasks.edit', $task) }}"
                       class="rounded-lg p-2 text-zinc-400 transition hover:bg-zinc-200/70 hover:text-zinc-900"
                       title="Edit" aria-label="Edit {{ $task->title }}">
                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                            <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                          onsubmit="return confirm('Delete “{{ $task->title }}”?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="rounded-lg p-2 text-zinc-400 transition hover:bg-rose-50 hover:text-rose-600"
                                title="Delete" aria-label="Delete {{ $task->title }}">
                            <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482 41.03 41.03 0 0 0-2.365-.298V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="px-6 py-20 text-center">
                <span class="mx-auto grid size-12 place-items-center rounded-2xl bg-zinc-100 text-zinc-400">
                    <svg class="size-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 0 0 3 3.5v13A1.5 1.5 0 0 0 4.5 18h11a1.5 1.5 0 0 0 1.5-1.5V7.621a1.5 1.5 0 0 0-.44-1.06l-4.12-4.122A1.5 1.5 0 0 0 11.378 2H4.5Zm2.25 8.5a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <p class="mt-4 text-sm font-semibold text-zinc-900">No tasks found</p>
                <p class="mt-1 text-sm text-zinc-500">
                    @if (array_filter($filters))
                        Nothing matches these filters.
                    @else
                        Get started by creating your first task.
                    @endif
                </p>
                <a href="{{ route('tasks.create') }}"
                   class="mt-5 inline-block rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                    New task
                </a>
            </div>
        @endforelse
    </div>

    @if ($tasks->hasPages())
        <div class="mt-8">
            {{ $tasks->links() }}
        </div>
    @endif
</x-layouts.app>
