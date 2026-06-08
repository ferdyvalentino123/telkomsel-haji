@extends("admin.layout")
@section("title", "Monitor Void Transaksi")
@section("content")

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title mb-0"><i class="fas fa-trash-alt"></i> Monitor Void Transaksi</h1>
        <p class="text-muted mb-0 mt-1" style="font-size:0.9rem;">Daftar transaksi yang telah dibatalkan (soft deleted).</p>
    </div>
    <div class="content-card px-4 py-3 text-center" style="border-left: 4px solid #dc3545; min-width: 200px;">
        <div class="text-muted" style="font-size:0.8rem;">TOTAL PENJUALAN TER-VOID</div>
        <div class="stat-value text-danger">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
    </div>
</div>

@if($groupedTransaksi->isEmpty())
    <div class="content-card text-center py-5">
        <i class="fas fa-ghost fa-3x text-muted mb-3 d-block"></i>
        <h5 class="text-muted">Tidak ada data transaksi ter-void.</h5>
    </div>
@else
    @foreach($groupedTransaksi as $date => $transactions)
        <div class="content-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="m-0">
                    <i class="far fa-calendar-alt me-2" style="color:#bc0007;"></i>
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                </h5>
                <span class="badge bg-secondary px-3 py-2">
                    Total Hari Ini: Rp {{ number_format($totalsPerDate[$date]['totalPenjualan'], 0, ',', '.') }}
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Sales</th>
                            <th>Produk</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th class="text-center">Waktu Hapus</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $trx)
                            <tr>
                                <td><strong class="text-danger">{{ $trx->id_transaksi }}</strong></td>
                                <td>
                                    <div class="fw-bold">{{ $trx->nama_pelanggan }}</div>
                                    <small class="text-muted">{{ $trx->nomor_telepon }}</small>
                                </td>
                                <td>{{ $trx->nama_sales }}</td>
                                <td>{{ $trx->produk->produk_nama ?? '-' }}</td>
                                <td><strong>Rp {{ number_format($trx->total_harga ?? ($trx->produk->produk_harga_akhir ?? 0), 0, ',', '.') }}</strong></td>
                                <td><span class="badge bg-light text-dark border">{{ $trx->metode_pembayaran ?? '-' }}</span></td>
                                <td class="text-center text-muted small">{{ $trx->deleted_at->format('H:i') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.monitor.destroy', $trx->id) }}" method="POST" class="d-inline void-destroy-form" data-id="{{ $trx->id_transaksi }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-destroy-void" data-id="{{ $trx->id_transaksi }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endif

@push('scripts')
<script>
document.querySelectorAll('.btn-destroy-void').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const form = this.closest('.void-destroy-form');
        Swal.fire({
            title: 'Hapus Permanen?',
            text: `Transaksi ${id} akan dihapus secara permanen dan tidak bisa dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#bc0007',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush

@endsection
