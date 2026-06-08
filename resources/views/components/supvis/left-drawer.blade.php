<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand">
            <span class="align-middle">{{ Auth::user()->name }}</span>
        </a>

        <style>
            .sidebar-header {
                padding: 1.5rem 1.5rem .375rem !important;
                font-size: .75rem !important;
                color: #ced4da !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                display: block !important;
            }
            .sidebar-nav {
                padding-left: 0 !important;
                list-style: none !important;
            }
        </style>
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Dashboard
            </li>
            <x-nav-link href="{{ route('kasir.home') }}" :active="request()->is('programhaji/kasir')">Home</x-nav-link>

            
            <li class="sidebar-header">
                Kelola Data
            </li>
            <x-nav-link href="{{ route('kasir.produk.index') }}" :active="request()->is('programhaji/kasir/produk*')">Produk</x-nav-link>
            <x-nav-link href="{{ route('kasir.merchandise.index') }}" :active="request()->is('programhaji/kasir/merchandise*')">Merch</x-nav-link>
            <x-nav-link href="{{ route('kasir.transaksi.approve') }}" :active="request()->is('programhaji/kasir/approvetransaksi')">Approve Transaksi</x-nav-link>
            <x-nav-link href="{{ route('kasir.transaksi.index') }}" :active="request()->is('programhaji/kasir/transaksi')">Riwayat Transaksi</x-nav-link>
            <x-nav-link href="{{ route('kasir.stock-history.index') }}" :active="request()->is('programhaji/kasir/stock-history*')">Pantau Stok</x-nav-link>
            <x-nav-link href="{{ route('kasir.budget_insentif.pantau') }}" :active="request()->is('programhaji/kasir/budget-insentif*')">Pantau Budget</x-nav-link>
            <x-nav-link href="{{ route('kasir.monitor.setoran') }}" :active="request()->is('programhaji/kasir/monitor/setoran')">Monitor Setoran</x-nav-link>
            <x-nav-link href="{{ route('kasir.monitor.void') }}" :active="request()->is('programhaji/kasir/monitor/void')">Void Transaksi</x-nav-link>

            <li class="sidebar-header">
                Manajemen User
            </li>
            <x-nav-link href="{{ route('kasir.add_sales') }}" :active="request()->is('programhaji/kasir/add-sales')">Add Sales</x-nav-link>
            <x-nav-link href="{{ route('kasir.history-setoran') }}" :active="request()->is('programhaji/kasir/history-setoran')">Checklist Sales</x-nav-link>

        </ul>
    </div>
</nav>


