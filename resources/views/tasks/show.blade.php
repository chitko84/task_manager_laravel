<x-layouts.app :title="$task->title">
    <div class="mx-auto max-w-3xl">
        <x-breadcrumbs :trail="['Tasks' => route('tasks.index'), $task->title => null]" />

        <div class="overflow-hidden rounded-2xl border border-zinc-200/80 bg-white shadow-sm">
            <div class="h-1.5 {{ match ($task->status) {
                'done' => 'bg-emerald-400',
                'in_progress' => 'bg-sky-400',
                default => 'bg-amber-400',
            } }}"></div>

            <div class="p-6 sm:p-8">
                <div class="flex flex-wrap items-center gap-2">
                    <x-status-badge :status="$task->status" />
                    <x-priority-badge :priority="$task->priority" />
                    @if ($task->due_date)
                        <x-due-chip :task="$task" />
                    @endif
                </div>

                <h1 class="mt-5 text-3xl font-semibold tracking-tight {{ $task->isDone() ? 'text-zinc-400 line-through decoration-zinc-300' : '' }}">
                    {{ $task->title }}
                </h1>

                <div class="mt-4 text-[15px] leading-relaxed text-zinc-600">
                    @if ($task->description)
                        {!! nl2br(e($task->description)) !!}
                    @else
                        <span class="italic text-zinc-400">No description provided.</span>
                    @endif
                </div>

                <dl class="mt-8 grid gap-6 rounded-xl bg-zinc-50 p-5 sm:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold text-zinc-400">DUE DATE</dt>
                        <dd class="mt-1 text-sm font-medium text-zinc-900">
                            {{ $task->due_date ? $task->due_date->format('M j, Y') : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-zinc-400">COMPLETED</dt>
                        <dd class="mt-1 text-sm font-medium text-zinc-900">
                            {{ $task->completed_at ? $task->completed_at->format('M j, Y H:i') : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-zinc-400">CREATED</dt>
                        <dd class="mt-1 text-sm font-medium text-zinc-900">{{ $task->created_at->diffForHumans() }}</dd>
                    </div>
                </dl>

                <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-zinc-100 pt-6">
                    <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm transition focus:outline-none focus:ring-4 {{ $task->isDone() ? 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200 focus:ring-zinc-500/20' : 'bg-emerald-600 text-white hover:bg-emerald-500 focus:ring-emerald-500/20' }}">
                            <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                            </svg>
                            {{ $task->isDone() ? 'Reopen task' : 'Mark as done' }}
                        </button>
                    </form>

                    <a href="{{ route('tasks.edit', $task) }}"
                       class="rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm transition hover:bg-zinc-50">
                        Edit
                    </a>

                    <a href="{{ route('tasks.index') }}"
                       class="rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-900">
                        Back to list
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
