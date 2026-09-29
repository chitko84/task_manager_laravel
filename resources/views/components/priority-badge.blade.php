@props(['priority'])

@php
    $styles = [
        'high' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'medium' => 'bg-zinc-100 text-zinc-700 ring-zinc-600/20',
        'low' => 'bg-zinc-50 text-zinc-500 ring-zinc-500/20',
    ];
@endphp

<span class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium capitalize ring-1 ring-inset {{ $styles[$priority] ?? '' }}">
    @if ($priority === 'high')
        <svg class="size-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L5.29 9.77a.75.75 0 0 1-1.08-1.04l5.25-5.5a.75.75 0 0 1 1.08 0l5.25 5.5a.75.75 0 1 1-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
        </svg>
    @endif
    {{ $priority }}
</span>
