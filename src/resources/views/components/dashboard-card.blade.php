@props([
    'title' => 'Título',
    'value' => 0,
])

<div class="card shadow-sm rounded-4 p-3 text-center h-100 bg-background text-white">
    <div class="mb-2">
        {{ $slot }}
    </div>
    <div class="fw-bold fs-4">{{ $value }}</div>
    <div class="">{{ $title }}</div>
</div>
