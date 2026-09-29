@if (session('status'))
    <div class="mb-8 flex items-center gap-3 rounded-2xl border border-emerald-200/80 bg-emerald-50/90 px-5 py-3.5 text-sm font-medium text-emerald-900 shadow-sm backdrop-blur">
        <span class="grid size-6 shrink-0 place-items-center rounded-full bg-emerald-500 text-white">
            <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
            </svg>
        </span>
        <span>{{ session('status') }}</span>
    </div>
@endif
