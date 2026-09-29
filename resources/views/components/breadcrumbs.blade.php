@props(['trail'])

<nav class="mb-5 flex items-center gap-1.5 text-sm text-zinc-400" aria-label="Breadcrumb">
    @foreach ($trail as $label => $url)
        @if (! $loop->first)
            <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
            </svg>
        @endif

        @if ($url)
            <a href="{{ $url }}" class="transition hover:text-zinc-800">{{ $label }}</a>
        @else
            <span class="max-w-56 truncate font-medium text-zinc-700">{{ $label }}</span>
        @endif
    @endforeach
</nav>
