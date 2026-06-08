@extends("travel.layout")
@section("title", "Detail Pemesanan")
@section("css")
<style>
    :root { --primary: #bc0007; --primary-dark: #8a0005; --primary-light: #ec1d24; --bg-light: #f5f7f9; --text-main: #333; --text-muted: #888; --border-color: #eaeaea; }
    .back-link { color: var(--primary); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; transition: color 0.3s; }
    .back-link:hover { color: var(--primary-dark); }
    .detail-card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); margin-bottom: 1.5rem; border: 1px solid var(--border-color); }
    .card-title { font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color); }
    .info-row { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color); }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: var(--text-muted); font-weight: 500; font-size: 0.9rem; }
    .info-value { font-weight: 700; color: var(--text-main); text-align: right; }
    .header-card { background: white; border-radius: 12px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; }
    .header-title { font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; }
    .header-meta { color: var(--text-muted); font-size: 0.9rem; }
    .header-meta span { font-weight: 700; color: var(--text-main); font-family: monospace; }
    .status-badge { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 20px; font-size: 0.9rem; font-weight: 700; }
    .status-pending { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .status-confirmed { background-color: rgba(37, 117, 252, 0.1); color: #2575fc; }
    .status-completed { background-color: rgba(67, 233, 123, 0.1); color: #43e97b; }
    .status-cancelled { background-color: rgba(245, 87, 108, 0.1); color: #f5576c; }
    .summary-box { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); border: 1px solid var(--border-color); }
    .summary-box.sticky-box { position: sticky; top: 2rem; }
    .summary-item { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.9rem; }
    .summary-item:last-child { border-bottom: none; }
    .summary-item span:last-child { font-weight: 600; color: var(--text-main); }
    .total-section { background: linear-gradient(135deg, rgba(188, 0, 7, 0.05) 0%, rgba(236, 29, 36, 0.05) 100%); border: 2px solid var(--primary); border-radius: 12px; padding: 1.5rem; margin: 1.5rem 0; text-align: center; }
    .total-label { color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 1px; }
    .total-value { font-size: 2rem; font-weight: 700; color: var(--primary); }
    .btn-action { display: block; width: 100%; padding: 1rem; border-radius: 8px; font-weight: 600; text-align: center; text-decoration: none; border: none; cursor: pointer; margin-bottom: 0.75rem; transition: all 0.3s ease; }
    .btn-primary { background-color: var(--primary); color: white; }
    .btn-primary:hover { background-color: var(--primary-dark); color: white; text-decoration: none; }
    .btn-secondary { background-color: #e9ecef; color: var(--text-main); border: 1px solid var(--border-color); }
    .btn-secondary:hover { background-color: #dee2e6; color: var(--text-main); text-decoration: none; }
</style>
@endsection
@section("content")
<div class="container-fluid" style="padding: 2rem 1.5rem;">
    <a href="{{ route('travel.transaksi.index') }}" class="back-link"><i class="fas fa-arrow-left"></i>Kembali ke Riwayat Pemesanan</a>
    <div class="header-card">
        <div>
            <h1 class="header-title">{{ $transaksi->produk->produk_nama ?? 'Paket Perjalanan' }}</h1>
            <p class="header-meta"><i class="fas fa-receipt me-2"></i>ID Pemesanan: <span>#{{ $transaksi->id_transaksi ?? $transaksi->id }}</span></p>
        </div>
        @php
            $status = $transaksi->status ?? 'pending';
            $statusClass = 'status-' . strtolower($status);
            if ($transaksi->is_paid || $status === 'lunas' || $status === 'success') {
                $statusClass = 'status-completed';
                $displayStatus = 'Dibayar';
            } else {
                $displayStatus = ['pending' => 'Menunggu Konfirmasi', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'][$status] ?? ucfirst($status);
            }
        @endphp
        <span class="status-badge {{ $statusClass }}"><i class="fas fa-info-circle me-2"></i>{{ $displayStatus }}</span>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="detail-card">
                <h2 class="card-title"><i class="fas fa-calendar-alt me-2" style="color: var(--primary);"></i>Informasi Pemesanan</h2>
                <div class="info-row">
                    <span class="info-label">Tanggal Pemesanan</span>
                    <span class="info-value">{{ $transaksi->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Pemesan</span>
                    <span class="info-value">{{ $transaksi->nama_pelanggan ?? 'Pelanggan' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $transaksi->email ?? '-' }}</span>
                </div>
                @if($grupTransaksi->count() == 1)
                    <div class="info-row">
                        <span class="info-label">No. Telepon</span>
                        <span class="info-value">{{ $transaksi->nomor_telepon ?? '-' }}</span>
                    </div>
                @endif
            </div>

            <div class="detail-card">
                <h2 class="card-title"><i class="fas fa-suitcase-rolling me-2" style="color: var(--primary);"></i>Detail Paket</h2>
                <div class="info-row">
                    <span class="info-label">Nama Paket</span>
                    <span class="info-value">{{ $transaksi->produk->produk_nama ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Harga Per Paket</span>
                    <span class="info-value">Rp {{ number_format($transaksi->produk->produk_harga_akhir ?? ($transaksi->produk->produk_harga ?? 0), 0, ",", ".") }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jumlah Paket / Jamaah</span>
                    <span class="info-value">{{ $grupTransaksi->count() }} Orang</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Pembayaran</span>
                    @if($transaksi->is_paid || $status === 'lunas' || $status === 'success')
                        <span class="info-value" style="color: #43e97b;"><i class="fas fa-check-circle me-1"></i>Sudah Dibayar</span>
                    @else
                        <span class="info-value" style="color: #ffc107;"><i class="fas fa-clock me-1"></i>Belum Dibayar</span>
                    @endif
                </div>
                <div class="info-row">
                    <span class="info-label">Status Injeksi Paket</span>
                    @php $allActivated = $grupTransaksi->every(fn($t) => $t->is_activated); @endphp
                    @if($allActivated)
                        <span class="info-value" style="color:#10b981;"><i class="fas fa-check-double me-1"></i>Paket Sudah Diinjeksi</span>
                    @else
                        <span class="info-value" style="color:#f59e0b;"><i class="fas fa-hourglass-half me-1"></i>Menunggu Injeksi Admin</span>
                    @endif
                </div>
            </div>

            @if($grupTransaksi->count() > 0)
                <div class="detail-card">
                    <h2 class="card-title"><i class="fas fa-users me-2" style="color: var(--primary);"></i>Daftar Jamaah / Nomor MSISDN</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" style="font-size: 0.9rem; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-3">No.</th>
                                    <th class="py-3 px-3">Nomor MSISDN</th>
                                    <th class="py-3 px-3">ID Transaksi</th>
                                    <th class="py-3 px-3 text-center">Status Injeksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grupTransaksi as $index => $item)
                                    <tr>
                                        <td class="py-3 px-3">{{ $index + 1 }}</td>
                                        <td class="py-3 px-3 font-weight-bold">{{ $item->nomor_telepon }}</td>
                                        <td class="py-3 px-3"><code style="font-size: 0.8rem; background-color: #f1f1f1; padding: 2px 6px; border-radius: 4px; color: #555;">{{ $item->id_transaksi }}</code></td>
                                        <td class="py-3 px-3 text-center">
                                            @if($item->is_activated)
                                                <span class="badge text-white" style="background-color: #28a745; font-size: 0.75rem; padding: 0.4rem 0.6rem; border-radius: 4px;"><i class="fas fa-check-circle me-1"></i>Aktif / Sukses</span>
                                            @elseif($item->status === 'cancelled' || $item->status === 'batal')
                                                <span class="badge text-white" style="background-color: #dc3545; font-size: 0.75rem; padding: 0.4rem 0.6rem; border-radius: 4px;"><i class="fas fa-times-circle me-1"></i>Batal</span>
                                            @else
                                                <span class="badge text-white" style="background-color: #f59e0b; font-size: 0.75rem; padding: 0.4rem 0.6rem; border-radius: 4px;"><i class="fas fa-hourglass-half me-1"></i>Menunggu Injeksi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- ===== SECTION BUKTI INJEKSI ===== --}}
            @php
                $firstActivated = $grupTransaksi->first(fn($t) => $t->is_activated && $t->bukti_injeksi);
                $semuaAktif = $grupTransaksi->every(fn($t) => $t->is_activated);
                $adaBukti = $firstActivated !== null;
            @endphp

            @if($semuaAktif && $adaBukti)
                <div class="detail-card" style="border: 2px solid #10b981; background: linear-gradient(135deg, #f0fdf4, #ecfdf5);">
                    <h2 class="card-title" style="color:#065f46; border-bottom-color:#a7f3d0;">
                        <i class="fas fa-check-double me-2" style="color:#10b981;"></i>Paket Telah Diinjeksi
                    </h2>
                    <div class="info-row">
                        <span class="info-label">Diinjeksi oleh</span>
                        <span class="info-value">{{ $firstActivated->injeksi_oleh ?? 'Admin' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Waktu Injeksi</span>
                        <span class="info-value">
                            {{ $firstActivated->injeksi_at ? \Carbon\Carbon::parse($firstActivated->injeksi_at)->format('d M Y H:i') : '-' }}
                        </span>
                    </div>
                    @if($firstActivated->catatan_injeksi)
                        <div class="info-row">
                            <span class="info-label">Catatan</span>
                            <span class="info-value">{{ $firstActivated->catatan_injeksi }}</span>
                        </div>
                    @endif
                    <div style="margin-top:1.25rem;">
                        <button type="button" onclick="showBuktiInjeksi('{{ asset('storage/' . $firstActivated->bukti_injeksi) }}')"
                           style="display:inline-flex;align-items:center;gap:8px;background:#10b981;color:#fff;padding:0.65rem 1.25rem;border-radius:8px;font-weight:600;border:none;margin-right:0.5rem;transition:all 0.3s;cursor:pointer;">
                            <i class="fas fa-image"></i> Lihat Bukti Injeksi
                        </button>
                        <a href="{{ route('travel.transaksi.konfirmasi', $transaksi->id) }}" target="_blank"
                           style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#065f46;border:2px solid #10b981;padding:0.65rem 1.25rem;border-radius:8px;font-weight:600;text-decoration:none;transition:all 0.3s;">
                            <i class="fas fa-file-alt"></i> Surat Konfirmasi Injeksi
                        </a>
                    </div>
                </div>
            @elseif($transaksi->is_paid || $transaksi->status === 'completed')
                <div class="detail-card" style="border: 2px solid #f59e0b; background: linear-gradient(135deg, #fffbeb, #fef3c7);">
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <div style="width:50px;height:50px;background:rgba(245,158,11,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-hourglass-half" style="font-size:1.5rem;color:#f59e0b;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#92400e;font-size:1rem;">Menunggu Proses Injeksi</div>
                            <div style="color:#b45309;font-size:0.88rem;margin-top:4px;">
                                Paket Anda sudah kami terima. Tim admin akan memproses injeksi ke nomor MSISDN jamaah.
                                Bukti injeksi akan tersedia di halaman ini setelah selesai diproses.
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-lg-4">
            <div class="summary-box sticky-box">
                <h2 class="card-title"><i class="fas fa-receipt me-2" style="color: var(--primary);"></i>Ringkasan</h2>
                <div class="summary-item">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($totalHarga ?? 0, 0, ",", ".") }}</span>
                </div>
                <div class="total-section">
                    <div class="total-label">Total Pembayaran</div>
                    <div class="total-value">Rp {{ number_format($totalHarga ?? 0, 0, ",", ".") }}</div>
                </div>
                @if($transaksi->status === 'pending' && !$transaksi->is_paid)
                    <a href="{{ route('travel.kolektif.pembayaran', $transaksi->id) }}" class="btn-action btn-primary"><i class="fas fa-credit-card me-2"></i>Lanjutkan Pembayaran</a>
                @endif
                @if($adaBukti ?? false)
                    <a href="{{ route('travel.transaksi.konfirmasi', $transaksi->id) }}" target="_blank" class="btn-action btn-secondary" style="background:#e8f5e9;color:#065f46;border:1px solid #a7f3d0;">
                        <i class="fas fa-file-alt me-2"></i>Surat Konfirmasi Injeksi
                    </a>
                    <button type="button" onclick="showBuktiInjeksi('{{ asset('storage/' . $firstActivated->bukti_injeksi) }}')" class="btn-action btn-secondary" style="background:#e8f5e9;color:#065f46;border:1px solid #a7f3d0;">
                        <i class="fas fa-image me-2"></i>Bukti Injeksi
                    </button>
                @endif
                <!-- <button class="btn-action" style="background-color: #2563eb; color: #fff; border: none; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; width: 100%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(37,99,235,0.1), 0 2px 4px -1px rgba(37,99,235,0.06);" onclick="window.print()" onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
                    <i class="fas fa-file-invoice-dollar me-2" style="font-size: 1.1rem;"></i>Unduh / Cetak Invoice
                </button> -->
                <a href="{{ route('travel.kolektif.index') }}" class="btn-action btn-secondary" style="margin-top: 10px;"><i class="fas fa-arrow-left me-2"></i>Beli Paket Baru</a>
            </div>
        </div>
    </div>
</div>
<script>
    function showBuktiInjeksi(url) {
        let isPdf = url.toLowerCase().endsWith('.pdf');
        if (isPdf) {
            Swal.fire({
                title: 'Bukti Injeksi Paket',
                html: `<iframe src="${url}" style="width:100%; height:500px; border:none; border-radius:8px;"></iframe>`,
                width: '800px',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Tutup'
            });
        } else {
            Swal.fire({
                title: 'Bukti Injeksi Paket',
                imageUrl: url,
                imageAlt: 'Bukti Injeksi',
                width: '600px',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Tutup'
            });
        }
    }
</script>
@endsection
