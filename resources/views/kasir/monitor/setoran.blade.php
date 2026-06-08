<x-Kasir.KasirLayouts><main class="content"><div class="container-fluid p-0">
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold text-dark"><i class="fas fa-money-bill-wave mr-2 text-success"></i> Monitor Setoran Sales</h2>
                <p class="text-muted">Pantau status setoran harian dari semua sales.</p>
            </div>
        </div>

        @foreach($groupedData as $date => $salesData)
            <div class="card mb-4 shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-light py-3 border-bottom-0">
                    <h5 class="mb-0 font-weight-bold"><i class="far fa-calendar-alt mr-2 text-primary"></i> Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                <tr>
                                    <th class="px-4 py-3">Nama Sales</th>
                                    <th>Total Penjualan</th>
                                    <th>Total Insentif</th>
                                    <th>Sudah Setor</th>
                                    <th>Belum Setor</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesData as $salesName => $stats)
                                    <tr>
                                        <td class="px-4 py-3 font-weight-bold text-dark">{{ $salesName }}</td>
                                        <td>Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($stats['total_insentif'], 0, ',', '.') }}</td>
                                        <td class="text-success font-weight-bold">Rp {{ number_format($stats['total_setor'], 0, ',', '.') }}</td>
                                        <td class="text-danger font-weight-bold">Rp {{ number_format($stats['total_pending'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if($stats['is_all_setor'])
                                                <span class="badge badge-success px-3 py-2" style="border-radius: 6px;"><i class="fas fa-check-circle mr-1"></i> LUNAS</span>
                                            @else
                                                <span class="badge badge-warning px-3 py-2" style="border-radius: 6px;"><i class="fas fa-clock mr-1"></i> PENDING</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        @if($groupedData->isEmpty())
            <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
                <div class="card-body">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada data setoran ditemukan.</h5>
                </div>
            </div>
        @endif
    </div>
</div></main></x-Kasir.KasirLayouts>


