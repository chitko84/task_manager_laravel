@props(['title' => 'Task Manager'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} · Task Manager</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-zinc-50 font-sans text-zinc-900 antialiased">
    <div class="pointer-events-none fixed inset-x-0 top-0 -z-10 h-96 bg-gradient-to-b from-indigo-100/70 via-zinc-50 to-transparent"></div>

    <header class="sticky top-0 z-40 border-b border-white/10 bg-zinc-950/90 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('tasks.index') }}" class="group flex items-center gap-3">
                <span class="grid size-9 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-900/40 transition group-hover:scale-105">
                    T
                </span>
                <span class="text-base font-semibold tracking-tight text-white">
                    Task Manager
                </span>
            </a>

            <nav class="flex items-center gap-1.5">
                <a href="{{ route('tasks.index') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('tasks.*') ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/5 hover:text-white' }}">
                    Tasks
                </a>
                <a href="{{ route('api.tasks.stats') }}"
                   class="hidden rounded-lg px-3 py-2 text-sm font-medium text-zinc-400 transition hover:bg-white/5 hover:text-white sm:block">
                    API
                </a>
                <a href="{{ route('tasks.create') }}"
                   class="ml-1.5 inline-flex items-center gap-1.5 rounded-lg bg-indigo-500 px-3.5 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-950/40 transition hover:bg-indigo-400">
                    <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                    </svg>
                    New task
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <x-flash />
        {{ $slot }}
    </main>

    <footer class="border-t border-zinc-200/80 py-8">
        <p class="text-center text-xs text-zinc-400">
            Built with Laravel {{ app()->version() }} · SQLite · Tailwind CSS
        </p>
    </footer>
</body>
</html>
