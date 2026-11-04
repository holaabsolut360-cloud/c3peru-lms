@props([
    'name',
    'label',
    'acceptedFileTypes' => 'video/mp4,video/webm',
    'multiple' => false,
    'required' => false,
    'id' => null,
])

<div {{ $attributes->merge(['class' => 'filepond-container']) }}>
    <label class="mb-2 block text-sm font-semibold text-fondo" @if ($id) for="{{ $id }}" @endif>{{ $label }}</label>
    <input
        @if ($id) id="{{ $id }}" @endif
        name="{{ $name }}"
        type="file"
        accept="{{ $acceptedFileTypes }}"
        data-filepond
        data-accepted-file-types="{{ $acceptedFileTypes }}"
        data-allow-multiple="{{ $multiple ? 'true' : 'false' }}"
        @if ($multiple) multiple @endif
        @if ($required) required @endif
        class="block w-full text-sm text-fondo"
    >
</div>
