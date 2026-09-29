<x-layouts.app title="Edit task">
    <div class="mx-auto max-w-2xl">
        <x-breadcrumbs :trail="['Tasks' => route('tasks.index'), $task->title => route('tasks.show', $task), 'Edit' => null]" />

        <h1 class="text-2xl font-semibold tracking-tight">Edit task</h1>
        <p class="mt-1 text-sm text-zinc-500">Adjust the details, status, or due date.</p>

        <form method="POST" action="{{ route('tasks.update', $task) }}"
              class="mt-6 rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @method('PUT')
            @include('tasks.partials.form-fields', ['task' => $task])

            <div class="mt-8 flex items-center gap-3 border-t border-zinc-100 pt-6">
                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
                    Save changes
                </button>
                <a href="{{ route('tasks.show', $task) }}"
                   class="rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">
                    Cancel
                </a>
            </div>
        </form>

        <div class="mt-6 flex items-center justify-between rounded-2xl border border-rose-200/70 bg-rose-50/60 px-5 py-4">
            <div>
                <p class="text-sm font-semibold text-rose-900">Delete this task</p>
                <p class="mt-0.5 text-sm text-rose-700/80">This removes it permanently. There is no undo.</p>
            </div>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                  onsubmit="return confirm('Delete “{{ $task->title }}”? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-xl border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-700 shadow-sm transition hover:bg-rose-100">
                    Delete
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
