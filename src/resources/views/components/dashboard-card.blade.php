@props([
    'title' => 'Título',
    'value' => 0,
])

<div class="card shadow-sm rounded-4 p-3 text-center h-100 text-white" style="background-color: rgba(0,0,0,0.3)">
    <div class="mb-2">
        {{ $slot }}
    </div>
    <div class="fw-bold fs-4">{{ $value }}</div>
    <div class="">{{ $title }}</div>
</div>
