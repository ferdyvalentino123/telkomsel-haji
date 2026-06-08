{{-- kelemahannya: tdk bisa ada ROUTE + SUBMIT sekaligus --}}

@props(['type' => 'button', 'variant' => 'primary', 'route' => null])

@php
    $styles = [
        'primary' => 'background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC); color: white; border: none; padding: 10px 20px; font-size: 1rem; border-radius: 5px; transition: background 0.3s ease, transform 0.2s ease;',
        'danger' => 'background: linear-gradient(135deg, #ff4b5c, #d32f2f); color: white; border: none; padding: 10px 20px; font-size: 1rem; border-radius: 5px; transition: background 0.3s ease, transform 0.2s ease;'
    ];
    
    $hoverStyles = [
        'primary' => 'background: linear-gradient(135deg, #2575FC, rgb(33, 226, 62));',
        'danger' => 'background: linear-gradient(135deg, #d32f2f, #ff4b5c); transform: scale(1.05);'
    ];
@endphp

@if($route)
    <a 
        href="{{ route($route) }}" 
        {{ $attributes->merge(['class' => "btn btn-$variant d-inline-block text-center"]) }}
        style="{{ $styles[$variant] }} padding: 10px 20px; display: inline-block; text-decoration: none;"
        onmouseover="this.style = '{{ $styles[$variant] }} {{ $hoverStyles[$variant] }}'"
        onmouseout="this.style = '{{ $styles[$variant] }}'"
    >
        {{ $slot }}
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $attributes->merge(['class' => "btn btn-$variant"]) }}
        style="{{ $styles[$variant] }}"
        onmouseover="this.style = '{{ $styles[$variant] }} {{ $hoverStyles[$variant] }}'"
        onmouseout="this.style = '{{ $styles[$variant] }}'"
    >
        {{ $slot }}
    </button>
@endif
