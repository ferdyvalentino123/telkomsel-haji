@props([
    'label', 
    'name', 
    'type' => 'text', 
    'placeholder' => '', 
    'rows' => 3, 
    'required' => false, 
    'value' => '', 
    'readonly' => false,
    'oninput' => ''
])

@php
    $readonlyClass = $readonly ? 'bg-light text-secondary border' : '';
@endphp

<div class="mb-3">
    <label for="{{ $name }}" class="form-label font-weight-bold">{{ $label }}</label>

    @if($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control {{ $readonlyClass }}" 
            placeholder="{{ $placeholder }}" 
            rows="{{ $rows }}" 
            {{ $required ? 'required' : '' }} 
            {{ $readonly ? 'readonly' : '' }}
            style="{{ $readonly ? 'background-color: #e9ecef; cursor: not-allowed;' : '' }}"
            {{ $oninput ? 'oninput='.$oninput : '' }}
        >{{ old($name, $value) }}</textarea>
    @else
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control {{ $readonlyClass }}" 
            placeholder="{{ $placeholder }}" 
            value="{{ old($name, $value) }}" 
            {{ $required ? 'required' : '' }} 
            {{ $readonly ? 'readonly' : '' }}
            style="{{ $readonly ? 'background-color: #e9ecef; cursor: not-allowed;' : '' }}"
            {{ $oninput ? 'oninput='.$oninput : '' }}
        >
    @endif
</div>
