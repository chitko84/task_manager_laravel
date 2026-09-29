@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'options' => [],
    'placeholder' => null,
    'required' => false,
])

@php
    $id = 'field-'.$name;
    $old = old($name, $value);
    $classes = 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm outline-none transition placeholder:text-zinc-400 focus:ring-4 '.
        ($errors->has($name)
            ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10'
            : 'border-zinc-200 focus:border-indigo-400 focus:ring-indigo-500/10');
@endphp

<div>
    <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-zinc-700">
        {{ $label }}
        @if ($required)
            <span class="text-rose-500">*</span>
        @endif
    </label>

    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="4" placeholder="{{ $placeholder }}" class="{{ $classes }}">{{ $old }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" class="{{ $classes }}">
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $old === (string) $optionValue)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $old }}"
               placeholder="{{ $placeholder }}" class="{{ $classes }}">
    @endif

    @error($name)
        <p class="mt-1.5 flex items-center gap-1 text-sm text-rose-600">
            <svg class="size-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
