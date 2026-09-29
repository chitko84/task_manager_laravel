@props(['status'])

@php
    $styles = [
        'pending' => 'bg-amber-100 text-amber-800 ring-amber-600/20',
        'in_progress' => 'bg-sky-100 text-sky-800 ring-sky-600/20',
        'done' => 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
    ];

    $labels = [
        'pending' => 'Pending',
        'in_progress' => 'In progress',
        'done' => 'Done',
    ];
@endphp

<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $styles[$status] ?? 'bg-zinc-100 text-zinc-700 ring-zinc-600/20' }}">
    {{ $labels[$status] ?? $status }}
</span>
