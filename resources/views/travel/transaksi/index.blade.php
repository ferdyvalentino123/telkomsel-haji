@extends("travel.layout")

@section("title", "Riwayat Pemesanan")

@section("css")
<style>
    :root {
        --primary: #bc0007;
        --primary-dark: #8a0005;
        --primary-light: #ec1d24;
        --bg-light: #f5f7f9;
        --text-main: #333;
        --text-muted: #888;
        --border-color: #eaeaea;
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .filter-section {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .filter-section .row {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-input, .filter-select {
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.85rem;
        flex: 1;
        min-width: 150px;
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(188, 0, 7, 0.1);
    }

    .transaction-item {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .transaction-item:hover {
        box-shadow: 0 8px 20px rgba(188, 0, 7, 0.15);
        transform: translateX(5px);
        color: inherit;
        text-decoration: none;
    }

    .transaction-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(188, 0, 7, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .transaction-details {
        flex: 1;
        min-width: 200px;
    }

    .transaction-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .transaction-meta {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .transaction-meta i {
        color: var(--primary);
        width: 16px;
    }

    .transaction-meta span {
        margin-right: 1rem;
    }

    .transaction-amount {
        text-align: right;
        margin-right: 1rem;
    }

    .amount-label {
        color: var(--text-muted);
        font-size: 0.75rem;
        margin-bottom: 0.15rem;
    }

    .amount-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
    }

    .status-badge {
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-block;
    }

    .status-pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .status-confirmed {
        background-color: rgba(37, 117, 252, 0.1);
        color: #2575fc;
    }

    .status-completed {
        background-color: rgba(67, 233, 123, 0.1);
        color: #43e97b;
    }

    .status-cancelled {
        background-color: rgba(245, 87, 108, 0.1);
        color: #f5576c;
    }

    .status-injected {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-awaiting {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background: white;
        border-radius: 12px;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 1rem;
        display: block;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    .btn-tsel {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        height: 100%;
    }

    .btn-tsel:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(188, 0, 7, 0.3);
        color: white;
        text-decoration: none;
    }

    .chevron-icon {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .transaction-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .transaction-amount {
            text-align: left;
            margin-right: 0;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-color);
        }

        .chevron-icon {
            display: none;
        }
    }
</style>
@endsection

@section("content")
<div class="container-fluid" style="padding: 1.5rem 1rem;">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="page-title"><i class="fas fa-receipt me-2" style="color: var(--primary);"></i>Riwayat Pemesanan</h1>
        <p class="page-subtitle">Lihat semua pemesanan paket perjalanan Anda</p>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('travel.transaksi.index') }}" class="d-flex gap-2 align-items-stretch flex-wrap">
            <input 
                type="text" 
                name="search" 
                class="filter-input"
                value="{{ request('search') }}" 
                placeholder="Cari berdasarkan paket atau nama..."
            >
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="btn-tsel">
                <i class="fas fa-search me-2"></i>Cari
            </button>
        </form>
    </div>

    <!-- Transactions List -->
    @if($transaksi && count($transaksi) > 0)
        <div class="mb-4">
            @foreach($transaksi as $t)
                <a href="{{ route('travel.transaksi.show', $t->id) }}" class="transaction-item">
                    <div class="transaction-header">
                        <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                            <div class="transaction-icon">
                                <i class="fas fa-suitcase-rolling"></i>
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-title">
                                    {{ $t->produk->produk_nama ?? 'Paket Perjalanan' }}
                                </div>
                                <div class="transaction-meta">
                                    <span>
                                        <i class="fas fa-user"></i>
                                        @if($t->total_jumlah > 1)
                                            Kolektif ({{ $t->total_jumlah }} Nomor / Jamaah)
                                        @else
                                            {{ $t->nomor_telepon ?? ($t->nama_pelanggan ?? 'Pelanggan') }}
                                        @endif
                                    </span>
                                    <span>
                                        <i class="fas fa-calendar"></i>
                                        {{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y H:i') : ($t->created_at ? $t->created_at->format('d M Y H:i') : '-') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="transaction-amount">
                            <div class="amount-label">Total Pembayaran</div>
                            <div class="amount-value">Rp {{ number_format($t->akumulasi_harga ?? ($t->total_harga ?? 0), 0, ",", ".") }}</div>
                        </div>

                        <div>
                            @php
                                $status = $t->status ?? 'pending';
                                $statusClass = 'status-' . strtolower($status);
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'confirmed' => 'Dikonfirmasi',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];
                                if ($t->is_paid || $status === 'lunas' || $status === 'success') {
                                    $statusClass = 'status-completed';
                                    $displayStatus = 'Dibayar';
                                } else {
                                    $displayStatus = $statusLabels[strtolower($status)] ?? ucfirst($status);
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                <i class="fas fa-info-circle me-1"></i>{{ $displayStatus }}
                            </span>
                            @if($t->is_paid || $status === 'completed' || $status === 'lunas' || $status === 'success')
                                @if($t->is_activated)
                                    <span class="status-badge status-injected" style="margin-top:0.35rem;display:inline-block;">
                                        <i class="fas fa-check-double me-1"></i>Paket Aktif
                                    </span>
                                @else
                                    <span class="status-badge status-awaiting" style="margin-top:0.35rem;display:inline-block;">
                                        <i class="fas fa-hourglass-half me-1"></i>Proses Injeksi
                                    </span>
                                @endif
                            @endif
                        </div>

                        <div class="chevron-icon">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($transaksi instanceof \Illuminate\Pagination\Paginator)
            <nav aria-label="Page navigation" class="mb-4">
                {{ $transaksi->links('pagination::bootstrap-5') }}
            </nav>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Belum Ada Pemesanan</h3>
            <p>
                @if(request('search') || request('status'))
                    Tidak ada pemesanan yang sesuai dengan pencarian Anda
                @else
                    Anda belum memiliki riwayat pemesanan. Mulai dengan memilih paket perjalanan.
                @endif
            </p>
            <a href="{{ route('travel.kolektif.index') }}" class="btn-primary">
                <i class="fas fa-plus me-2"></i>Buat Pemesanan Baru
            </a>
        </div>
    @endif
</div>
@endsection
