@extends("admin.layout")
@section("title", "Monitor Setoran Sales")
@section("content")

<div class="mb-4">
    <h1 class="page-title mb-0"><i class="fas fa-money-bill-wave"></i> Monitor Setoran Sales</h1>
    <p class="text-muted mt-1" style="font-size:0.9rem;">Pantau status setoran harian dari semua sales.</p>
</div>

@if($groupedData->isEmpty())
    <div class="content-card text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
        <h5 class="text-muted">Tidak ada data setoran ditemukan.</h5>
    </div>
@else
    @foreach($groupedData as $date => $salesData)
        <div class="content-card mb-4">
            <div class="card-header">
                <h5 class="m-0">
                    <i class="far fa-calendar-alt me-2" style="color:#bc0007;"></i>
                    Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Nama Sales</th>
                            <th>Total Penjualan</th>
                            <th>Total Insentif</th>
                            <th>Sudah Setor</th>
                            <th>Belum Setor</th>
                            <th class="text-center">Status Sales</th>
                            <th class="text-center">Verifikasi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesData as $salesName => $stats)
                            <tr>
                                <td><strong>{{ $salesName }}</strong></td>
                                <td>Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($stats['total_insentif'], 0, ',', '.') }}</td>
                                <td class="text-success fw-bold">Rp {{ number_format($stats['total_setor'], 0, ',', '.') }}</td>
                                <td class="text-danger fw-bold">Rp {{ number_format($stats['total_pending'], 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($stats['is_all_setor'])
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> DISETOR
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="fas fa-clock me-1"></i> BELUM SETOR
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($stats['is_all_verified'])
                                        <span class="badge bg-primary px-3 py-2">
                                            <i class="fas fa-user-check me-1"></i> TERVERIFIKASI
                                        </span>
                                    @elseif($stats['is_all_setor'] && !$stats['is_all_verified'])
                                        <form action="{{ route('admin.monitor.setoran.approve') }}" method="POST" class="d-inline" onsubmit="confirmApprove(event, this, '{{ $salesName }}')">
                                            @csrf
                                            <input type="hidden" name="date" value="{{ $date }}">
                                            <input type="hidden" name="sales_name" value="{{ $salesName }}">
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill fw-bold">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmApprove(e, form, salesName) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Penerimaan',
            text: `Anda yakin telah menerima fisik uang setoran dari ${salesName} untuk tanggal ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Terima!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>

@endsection
