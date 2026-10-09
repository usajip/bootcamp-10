@props(['type' => 'info', 'id' => null])

<div class="alert alert-{{ $type }}" id="{{ $id }}">
    {{ $slot }}
</div>