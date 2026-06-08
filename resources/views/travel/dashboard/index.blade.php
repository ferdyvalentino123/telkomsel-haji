@extends("travel.layout")
@section("title", "Dashboard Travel")
@section("css")
<style>
    :root { --primary: #bc0007; --primary-dark: #8a0005; --primary-light: #ec1d24; --bg-light: #f5f7f9; --text-main: #333; --text-muted: #888; --border-color: #eaeaea; }
    body { background-color: var(--bg-light); }
    .page-title { font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; }
    .stat-card { background: white; border-radius: 12px; padding: 0.9rem 1.1rem; border-left: 4px solid var(--primary); border-top: none; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.3s; display: flex; align-items: center; gap: 12px; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(188, 0, 7, 0.12); }
    .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; margin-bottom: 0; }
    .stat-icon-1 { background-color: rgba(188, 0, 7, 0.1); color: var(--primary); }
    .stat-icon-2 { background-color: rgba(67, 233, 123, 0.1); color: #43e97b; }
    .stat-icon-3 { background-color: rgba(37, 117, 252, 0.1); color: #2575fc; }
    .stat-label { color: var(--text-muted); font-size: 0.72rem; margin-bottom: 1px; }
    .stat-value { font-size: 1.3rem; font-weight: 700; line-height: 1.1; }
    .stat-text { flex: 1; min-width: 0; }
    .welcome-banner { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); border-radius: 12px; padding: 1.2rem 1.5rem; color: white; margin-bottom: 1.25rem; box-shadow: 0 8px 20px rgba(188, 0, 7, 0.2); }
    .quick-link-card { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); border-radius: 12px; padding: 1.2rem; color: white; text-decoration: none; display: block; height: 100%; transition: all 0.3s; }
    .quick-link-card:hover { transform: translateY(-5px); color: white; }
    .quick-link-icon { font-size: 1.6rem; margin-bottom: 0.6rem; opacity: 0.9; }
    .quick-link-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.3rem; }
    .badge { padding: 0.3rem 0.6rem; font-weight: 600; border-radius: 6px; font-size: 0.75rem; }
    .badge-tsel { background-color: rgba(188, 0, 7, 0.1); color: var(--primary); }
    .badge-success { background-color: rgba(67, 233, 123, 0.1); color: #43e97b; }
    @media (max-width: 576px) {
        .stat-card { padding: 0.75rem 0.9rem; gap: 10px; }
        .stat-icon { width: 36px; height: 36px; font-size: 14px; }
        .stat-value { font-size: 1.1rem; }
        .stat-label { font-size: 0.68rem; }
        .welcome-banner h1 { font-size: 1rem !important; }
        .welcome-banner p { font-size: 0.8rem; }
        .container-fluid { padding: 1rem !important; }
    }
</style>
@endsection

@section("content")
<div class="container-fluid" style="padding: 2rem 1.5rem;">
    <div class="welcome-banner mb-3">
        <h1 class="text-white" style="font-size: 1.3rem; font-weight: 700; margin-bottom: 0.3rem;"><i class="fas fa-plane text-white"></i> Dashboard Travel Portal</h1>
        <p style="margin-bottom: 0; font-size: 0.88rem;">Selamat datang, <strong>{{ Auth::user()->name ?? 'Mitra Travel' }}</strong>. Kelola paket perjalanan dan transaksi Anda.</p>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-sm-4 mb-2">
            <div class="stat-card">
                <div class="stat-icon stat-icon-1"><i class="fas fa-suitcase-rolling"></i></div>
                <div class="stat-text">
                    <div class="stat-label">Total Paket</div>
                    <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4 mb-2">
            <div class="stat-card">
                <div class="stat-icon stat-icon-2"><i class="fas fa-receipt"></i></div>
                <div class="stat-text">
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value">{{ $totalTransactions ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4 mb-2">
            <div class="stat-card">
                <div class="stat-icon stat-icon-3"><i class="fas fa-rupiah-sign"></i></div>
                <div class="stat-text">
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value" style="font-size: 1.05rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Rp {{ number_format($totalRevenue ?? 0, 0, ",", ".") }}</div>
                </div>
            </div>
        </div>
    </div>



    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <a href="{{ route('travel.kolektif.index') }}" class="quick-link-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="quick-link-icon"><i class="fas fa-users"></i></div>
                <div class="quick-link-title">Beli Kolektif</div>
                <p style="font-size: 0.8rem; opacity: 0.9;">Beli banyak nomor sekaligus</p>
            </a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="{{ route('travel.laporan.index') }}" class="quick-link-card" style="background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);">
                <div class="quick-link-icon"><i class="fas fa-chart-bar"></i></div>
                <div class="quick-link-title">Laporan Rekap</div>
                <p style="font-size: 0.8rem; opacity: 0.9;">Lihat performa & histori</p>
            </a>
        </div>
    </div>

    <!-- Menambahkan Tabel Transaksi Belum Diinjeksi -->
    @if(count($uninjectedTransactions) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px; border-left: 4px solid #ffc107 !important;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-warning"><i class="fas fa-exclamation-triangle mr-2"></i> Perlu Perhatian: Transaksi Belum Diinjeksi (Belum Aktif)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr style="font-size: 0.75rem; text-transform: uppercase; color: #888;">
                                    <th class="pl-4">Pelanggan / Grup</th>
                                    <th>Paket</th>
                                    <th>Tanggal</th>
                                    <th class="text-right pr-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($uninjectedTransactions as $trx)
                                    <tr>
                                        <td class="pl-4">
                                            @if($trx->total_jumlah > 1)
                                                <span class="font-weight-bold text-dark">Kolektif ({{ $trx->total_jumlah }} Nomor)</span>
                                            @else
                                                <span class="font-weight-bold text-dark">{{ $trx->nomor_telepon ?? ($trx->nama_pelanggan ?? 'Pelanggan') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $trx->produk->produk_nama ?? 'Paket N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                                        <td class="text-right pr-4">
                                            <a href="{{ route('travel.transaksi.show', $trx->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 6px;">
                                                <i class="fas fa-eye mr-1"></i> Detail Paket
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-history mr-2 text-primary"></i> Transaksi Terakhir</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr style="font-size: 0.75rem; text-transform: uppercase; color: #888;">
                                    <th class="pl-4">Pelanggan</th>
                                    <th>Paket</th>
                                    <th>Status</th>
                                    <th class="text-right pr-4">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $trx)
                                    <tr>
                                        <td class="pl-4">
                                            @if($trx->total_jumlah > 1)
                                                <a href="{{ route('travel.transaksi.show', $trx->id) }}" class="font-weight-bold text-decoration-none text-danger">Kolektif ({{ $trx->total_jumlah }} Nomor / Jamaah)</a>
                                            @else
                                                <a href="{{ route('travel.transaksi.show', $trx->id) }}" class="font-weight-bold text-decoration-none text-dark">{{ $trx->nomor_telepon ?? ($trx->nama_pelanggan ?? 'Pelanggan') }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $trx->produk->produk_nama ?? 'Paket N/A' }}</td>
                                        <td>
                                            @if(in_array(strtolower($trx->status), ['completed', 'success', 'sukses']))
                                                <span class="badge badge-success">Sukses</span>
                                            @elseif(in_array(strtolower($trx->status), ['pending', 'booked', 'menunggu']))
                                                <span class="badge badge-warning" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">Pending</span>
                                            @elseif(in_array(strtolower($trx->status), ['cancelled', 'batal', 'gagal', 'failed']))
                                                <span class="badge badge-danger" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545;">Batal</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($trx->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right pr-4 font-weight-bold">Rp {{ number_format($trx->akumulasi_harga ?? ($trx->total_harga ?? 0), 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px; height: 100%;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-map-marked-alt mr-2" style="color: var(--primary);"></i> Paket Favorit</h5>
                </div>
                <div class="card-body">
                    @foreach($featuredProducts as $fp)
                        <div class="d-flex align-items-center mb-3 p-2 border-bottom pb-3">
                            <div class="bg-light rounded p-2 mr-3" style="color: var(--primary);">
                                <i class="fas fa-plane-departure"></i>
                            </div>
                            <div style="flex: 1;">
                                <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">{{ $fp->produk_nama }}</div>
                                <div class="font-weight-bold" style="font-size: 0.85rem; color: var(--primary);">Rp {{ number_format($fp->produk_harga_akhir, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                    <a href="{{ route('travel.kolektif.index') }}" class="btn btn-outline-danger btn-block btn-sm mt-3" style="border-color: var(--primary); color: var(--primary);">Jelajahi Paket Lainnya</a>
                </div>
            </div>
        </div>
    </div>

    @if($lowStockProducts > 0 || $outOfStockProducts > 0)
        <div class="alert alert-warning" style="border-left: 4px solid var(--primary); background-color: rgba(255, 193, 7, 0.1);">
            <strong>âš ï¸ Perhatian Stok:</strong>
            @if($lowStockProducts > 0)
                {{ $lowStockProducts }} paket stok menipis.
            @endif
            @if($outOfStockProducts > 0)
                {{ $outOfStockProducts }} paket habis.
            @endif
        </div>
    @endif
</div>
@endsection
