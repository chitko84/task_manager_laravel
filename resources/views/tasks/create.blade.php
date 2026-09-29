<x-layouts.app title="New task">
    <div class="mx-auto max-w-2xl">
        <x-breadcrumbs :trail="['Tasks' => route('tasks.index'), 'New' => null]" />

        <h1 class="text-2xl font-semibold tracking-tight">Create a task</h1>
        <p class="mt-1 text-sm text-zinc-500">Capture what needs doing, then triage it from the board.</p>

        <form method="POST" action="{{ route('tasks.store') }}"
              class="mt-6 rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @include('tasks.partials.form-fields', ['task' => null])

            <div class="mt-8 flex items-center gap-3 border-t border-zinc-100 pt-6">
                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
                    Create task
                </button>
                <a href="{{ route('tasks.index') }}"
                   class="rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
