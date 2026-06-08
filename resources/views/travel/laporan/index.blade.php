@extends("travel.layout")
@section("title", "Laporan & Rekap")

@section("css")
<style>
    :root { --primary: #bc0007; --primary-dark: #8a0005; --primary-light: #ec1d24; }
    .card { border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
    .stat-card { border-left: 5px solid var(--primary); }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: #333; }
    .stat-label { font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; }
    .btn-tsel { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 600; }
    .btn-tsel:hover { color: white; opacity: 0.9; }
    .status-badge { padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
    .badge-completed { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    .badge-booked { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
</style>
@endsection

@section("content")
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="font-weight-bold">Laporan & Rekap Transaksi</h2>
            <p class="text-muted">Pantau performa penjualan paket perjalanan Anda</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('travel.laporan.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-uppercase">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-uppercase">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-tsel btn-block">
                        <i class="fas fa-filter mr-2"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value">{{ $totalTransactions }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card" style="border-left-color: #28a745;">
                <div class="card-body">
                    <div class="stat-label">Pendapatan Bersih</div>
                    <div class="stat-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="card stat-card" style="border-left-color: #ffc107;">
                <div class="card-body">
                    <div class="stat-label">Booking Aktif</div>
                    <div class="stat-value" style="color: #ffc107;">{{ $bookedCount }}</div>
                </div>
            </div>
        </div> -->
        <div class="col-md-3">
            <div class="card stat-card" style="border-left-color: #17a2b8;">
                <div class="card-body">
                    <div class="stat-label">Sukses Terinjeksi</div>
                    <div class="stat-value" style="color: #17a2b8;">{{ $completedCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card mt-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 font-weight-bold">Daftar Transaksi Periode Ini</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="pl-4">Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td class="pl-4">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($trx->total_jumlah > 1)
                                        <a href="{{ route('travel.transaksi.show', $trx->id) }}" class="font-weight-bold text-decoration-none text-danger">Kolektif ({{ $trx->total_jumlah }} Nomor / Jamaah)</a>
                                    @else
                                        <a href="{{ route('travel.transaksi.show', $trx->id) }}" class="font-weight-bold text-decoration-none text-dark">{{ $trx->nomor_telepon ?? ($trx->nama_pelanggan ?? 'Pelanggan') }}</a>
                                    @endif
                                </td>
                                <td>{{ $trx->produk->produk_nama ?? 'Paket N/A' }}</td>
                                <td class="font-weight-bold text-dark">Rp {{ number_format($trx->akumulasi_harga ?? ($trx->total_harga ?? 0), 0, ',', '.') }}</td>
                                <td>{{ $trx->metode_pembayaran }}</td>
                                <td>
                                    @if($trx->status == 'completed')
                                        <span class="status-badge badge-completed">Sukses</span>
                                    @elseif($trx->status == 'booked')
                                        <span class="status-badge badge-booked">Booking</span>
                                    @else
                                        <span class="status-badge badge-secondary">{{ $trx->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-muted">Tidak ada transaksi ditemukan untuk periode ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

