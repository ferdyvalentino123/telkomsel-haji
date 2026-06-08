<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand d-flex flex-column align-items-center pt-4" href="{{ route('travel.home') }}">
            <img src="{{ asset('admin_asset/img/photos/logo_telkomsel.png') }}" alt="Logo" style="height: 40px; filter: brightness(0) invert(1); margin-bottom: 10px;">
            <span class="align-middle" style="font-size: 0.85rem; opacity: 0.8;">PONDOK HAJI</span>
            <span class="align-middle" style="font-size: 0.85rem; opacity: 0.8;">Travel Panel</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header" style="opacity: 0.5; font-size: 0.65rem;">
                MENU UTAMA
            </li>

            <li class="sidebar-item {{ request()->routeIs('travel.home') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('travel.home') }}">
                    <i class="align-middle fas fa-home"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('travel.kolektif.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('travel.kolektif.index') }}">
                    <i class="align-middle fas fa-users"></i> <span class="align-middle">Pembelian Kolektif</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('travel.transaksi.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('travel.transaksi.index') }}">
                    <i class="align-middle fas fa-history"></i> <span class="align-middle">Riwayat Pemesanan</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('travel.laporan.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('travel.laporan.index') }}">
                    <i class="align-middle fas fa-chart-bar"></i> <span class="align-middle">Laporan & Rekap</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

