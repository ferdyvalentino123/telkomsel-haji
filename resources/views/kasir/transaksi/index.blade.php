<x-Kasir.KasirLayouts><main class="content"><div class="container-fluid p-0">
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title mb-0"><i class="fas fa-receipt"></i> Riwayat Transaksi</h1>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>ID Transaksi</th><th>Pelanggan</th><th>Sales</th><th>Jumlah</th><th>Status</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                        <tr>
                            <td>#{{ $t->id_transaksi }}</td>
                            <td>{{ $t->nama_pelanggan }}</td>
                            <td>{{ $t->nama_sales ?: '-' }}</td>
                            <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if($t->is_paid || in_array($t->status, ['lunas', 'success']))
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ $t->tanggal_transaksi?->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5">Tidak ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div></main></x-Kasir.KasirLayouts>


