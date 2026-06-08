@props(['active'=> false])

<li class="{{ $active ? 'active' : ''}} sidebar-item">
    <a {{ $attributes }} class="sidebar-link" href="/dashboard">
        <i class="align-middle"></i> <span class="align-middle">{{ $slot }}</span>
    </a>
</li>
