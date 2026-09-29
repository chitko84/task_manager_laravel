@props(['label', 'count', 'accent', 'icon'])

<div class="group relative overflow-hidden rounded-2xl border border-zinc-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="absolute inset-x-0 top-0 h-1 {{ $accent }}"></div>
    <div class="flex items-center justify-between">
        <span class="text-sm font-medium text-zinc-500">{{ $label }}</span>
        <span class="grid size-9 place-items-center rounded-xl bg-zinc-100 text-zinc-500 transition group-hover:bg-zinc-200/70">
            {{ $icon }}
        </span>
    </div>
    <p class="mt-3 text-3xl font-semibold tabular-nums tracking-tight">{{ $count }}</p>
</div>
