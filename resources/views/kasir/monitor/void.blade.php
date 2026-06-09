<x-kasir.layouts><main class="content"><div class="container-fluid p-0">
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="font-weight-bold text-dark"><i class="fas fa-trash-alt mr-2 text-danger"></i> Monitor Void Transaksi</h2>
                <p class="text-muted">Daftar transaksi yang telah dibatalkan (soft deleted).</p>
            </div>
            <div class="col-md-4 text-right">
                <div class="card shadow-sm border-0 bg-danger text-white p-3" style="border-radius: 12px;">
                    <small class="text-uppercase font-weight-bold opacity-75">Total Penjualan Ter-Void</small>
                    <h3 class="mb-0">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        @foreach($groupedTransaksi as $date => $transactions)
            <div class="card mb-4 shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="far fa-calendar-alt mr-2 text-primary"></i> {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h5>
                    <span class="badge badge-secondary px-3 py-2">Total Hari Ini: Rp {{ number_format($totalsPerDate[$date]['totalPenjualan'], 0, ',', '.') }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                <tr>
                                    <th class="px-4 py-3">ID Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th>Sales</th>
                                    <th>Produk</th>
                                    <th>Nominal</th>
                                    <th>Metode</th>
                                    <th class="text-center">Waktu Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $trx)
                                    <tr>
                                        <td class="px-4 py-3 font-weight-bold text-danger">{{ $trx->id_transaksi }}</td>
                                        <td>
                                            <div class="font-weight-bold">{{ $trx->nama_pelanggan }}</div>
                                            <small class="text-muted">{{ $trx->nomor_telepon }}</small>
                                        </td>
                                        <td>{{ $trx->nama_sales }}</td>
                                        <td>{{ $trx->produk->produk_nama ?? '-' }}</td>
                                        <td class="font-weight-bold">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                        <td><span class="badge badge-light border">{{ $trx->metode_pembayaran }}</span></td>
                                        <td class="text-center text-muted small">{{ $trx->deleted_at->format('H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        @if($groupedTransaksi->isEmpty())
            <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
                <div class="card-body">
                    <i class="fas fa-ghost fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada data transaksi ter-void.</h5>
                </div>
            </div>
        @endif
    </div>
</div></main></x-kasir.layouts>


