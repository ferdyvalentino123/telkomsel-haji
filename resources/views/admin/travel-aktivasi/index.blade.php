@extends('admin.layout')

@section('title', 'Aktivasi Travel - Injeksi Paket')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #bc0007 0%, #e2241d 100%);
        border-radius: 16px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(188, 0, 7, 0.2);
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l2.5 12.5L45 15l-12.5 2.5L30 30l-2.5-12.5L15 15l12.5-2.5L30 0zm0 30l2.5 12.5L45 45l-12.5 2.5L30 60l-2.5-12.5L15 45l12.5-2.5L30 30z' fill='%23ffffff' fill-opacity='0.05'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }
    .page-header > * { position: relative; z-index: 1; }

    .stat-mini {
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        padding: 12px 20px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        min-width: 110px;
        backdrop-filter: blur(4px);
    }
    .stat-mini .num { font-size: 1.8rem; font-weight: 800; }
    .stat-mini .label { font-size: 0.75rem; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px; }

    .filter-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e8e8e8;
    }

    .trx-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e8e8e8;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        margin-bottom: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .trx-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.09); transform: translateY(-1px); }
    .trx-card.is-done { border-left: 4px solid #10b981; }
    .trx-card.is-pending { border-left: 4px solid #f59e0b; }

    .trx-body { padding: 18px 22px; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
        margin-top: 14px;
    }
    .info-item { display: flex; flex-direction: column; }
    .info-item .lbl { font-size: 0.73rem; color: #888; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px; }
    .info-item .val { font-size: 0.92rem; font-weight: 600; color: #1a1c1c; }

    .badge-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700;
    }
    .badge-pending { background: #fff8e1; color: #b7791f; border: 1px solid #f6d860; }
    .badge-done { background: #e8f5e9; color: #1a7a4a; border: 1px solid #a5d6a7; }

    .msisdn-collapse {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px 16px;
        margin-top: 12px;
        border: 1px solid #e9ecef;
        font-size: 0.85rem;
    }
    .msisdn-chip {
        display: inline-flex; align-items: center; gap: 4px;
        background: #fff; border: 1px solid #dee2e6;
        border-radius: 6px; padding: 4px 10px;
        font-size: 0.82rem; font-family: monospace;
        margin: 2px;
        color: #333;
    }

    .btn-upload-trv {
        background: linear-gradient(135deg, #bc0007, #e2241d);
        color: #fff; border: none; padding: 9px 20px;
        border-radius: 8px; font-weight: 600; font-size: 0.88rem;
        cursor: pointer; transition: all 0.2s ease;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-upload-trv:hover { opacity: 0.9; transform: translateY(-1px); color: #fff; }

    .btn-view-bukti {
        background: #e3f2fd; color: #0d47a1;
        border: 1px solid #bbdefb; padding: 7px 16px;
        border-radius: 8px; font-weight: 600; font-size: 0.85rem;
        cursor: pointer; transition: all 0.2s ease;
        display: inline-flex; align-items: center; gap: 7px;
        text-decoration: none;
    }
    .btn-view-bukti:hover { background: #0d47a1; color: #fff; }

    .empty-state { text-align: center; padding: 60px 20px; background: #fff; border-radius: 14px; }
    .empty-state i { font-size: 3.5rem; color: #e0e0e0; margin-bottom: 14px; }

    .injeksi-done-box {
        background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
        border: 1.5px solid #a5d6a7;
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 12px;
        font-size: 0.85rem;
        color: #1a7a4a;
    }

    @media (max-width: 576px) {
        .info-grid { grid-template-columns: 1fr 1fr; }
        .page-header { padding: 18px; }
        .stat-mini { min-width: 90px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="text-white" style="font-size:1.6rem; font-weight:800; margin:0;">
                    <i class="fas fa-plane-arrival me-2"></i> Aktivasi Travel — Injeksi Paket Jamaah
                </h1>
                <p style="margin:4px 0 0; opacity:0.85; font-size:0.9rem;">
                    Upload bukti injeksi untuk transaksi travel yang sudah lunas
                </p>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <div class="stat-mini">
                    <div class="num">{{ $totalPending }}</div>
                    <div class="label">Menunggu</div>
                </div>
                <div class="stat-mini" style="background: rgba(16,185,129,0.25);">
                    <div class="num">{{ $totalDone }}</div>
                    <div class="label">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.travel-aktivasi.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1">Cari Transaksi</label>
                <input type="text" name="search" class="form-control" placeholder="Nama / No. MSISDN / ID Transaksi"
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1">Filter Travel</label>
                <select name="travel_id" class="form-select">
                    <option value="">Semua Travel</option>
                    @foreach($travelList as $travel)
                        <option value="{{ $travel->id }}" {{ request('travel_id') == $travel->id ? 'selected' : '' }}>
                            {{ $travel->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1">Status Injeksi</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Diinjeksi</option>
                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Sudah Diinjeksi</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-danger flex-fill">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.travel-aktivasi.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- List Transaksi --}}
    @if($transaksis->isEmpty())
        <div class="empty-state">
            <i class="fas fa-check-circle d-block" style="color: #10b981;"></i>
            <h5 class="text-muted mt-2">Tidak ada transaksi yang ditemukan.</h5>
            <p class="text-muted small">Coba ubah filter atau belum ada transaksi travel yang lunas.</p>
        </div>
    @else
        @foreach($transaksis as $trx)
            @php
                $msisdns = $trx->semua_msisdn ? explode(',', $trx->semua_msisdn) : [$trx->nomor_telepon];
                $jumlahMsisdn = $trx->total_msisdn ?? count($msisdns);
                $grupId = 'grup_' . $trx->id;
            @endphp
            <div class="trx-card {{ $trx->is_activated ? 'is-done' : 'is-pending' }}">
                <div class="trx-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        {{-- Info utama --}}
                        <div style="flex:1;">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="badge-status {{ $trx->is_activated ? 'badge-done' : 'badge-pending' }}">
                                    @if($trx->is_activated)
                                        <i class="fas fa-check-circle"></i> SUDAH DIINJEKSI
                                    @else
                                        <i class="fas fa-hourglass-half"></i> MENUNGGU INJEKSI
                                    @endif
                                </span>
                                <span class="text-muted small">#{{ $trx->id_transaksi }}</span>
                                <span class="badge bg-secondary" style="font-size:0.7rem;">
                                    <i class="fas fa-users me-1"></i>{{ $jumlahMsisdn }} MSISDN
                                </span>
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 700; color: #1a1c1c;">
                                {{ $trx->supervisor->name ?? 'Travel Tidak Dikenal' }}
                            </div>
                            <div class="text-muted small">
                                <i class="fas fa-box me-1"></i>
                                {{ $trx->produk->produk_nama ?? '-' }}
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            @if($trx->is_activated && $trx->bukti_injeksi)
                                <button type="button" onclick="showBuktiInjeksi('{{ str_starts_with($trx->bukti_injeksi, 'http') ? $trx->bukti_injeksi : asset('storage/' . $trx->bukti_injeksi) }}')" class="btn-view-bukti">
                                    <i class="fas fa-image"></i> Lihat Bukti
                                </button>
                            @endif
                            <button class="btn-upload-trv btn-open-upload"
                                data-id="{{ $trx->id }}"
                                data-msisdns="{{ implode(', ', $msisdns) }}"
                                data-nama="{{ $trx->supervisor->name ?? 'Travel' }}"
                                data-paket="{{ $trx->produk->produk_nama ?? '-' }}"
                                data-jumlah="{{ $jumlahMsisdn }}">
                                <i class="fas fa-upload"></i>
                                {{ $trx->is_activated ? 'Perbarui Bukti' : 'Upload Bukti Injeksi' }}
                            </button>
                        </div>
                    </div>

                    {{-- Detail info grid --}}
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="lbl">Paket</span>
                            <span class="val">{{ $trx->produk->produk_nama ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Travel</span>
                            <span class="val">{{ $trx->supervisor->name ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Total Jamaah</span>
                            <span class="val">{{ $jumlahMsisdn }} Orang</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Total Bayar</span>
                            <span class="val text-danger">Rp {{ number_format($trx->akumulasi_harga ?? $trx->total_harga ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Tanggal Transaksi</span>
                            <span class="val">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Status Bayar</span>
                            <span class="val text-success"><i class="fas fa-check-circle me-1"></i>Lunas</span>
                        </div>
                    </div>

                    {{-- Daftar MSISDN --}}
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-secondary" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $grupId }}"
                            aria-expanded="false">
                            <i class="fas fa-sim-card me-1"></i> Lihat Daftar MSISDN ({{ $jumlahMsisdn }} Nomor)
                        </button>
                        <div class="collapse" id="{{ $grupId }}">
                            <div class="msisdn-collapse">
                                <div class="fw-semibold mb-2 text-muted small">DAFTAR NOMOR MSISDN / JAMAAH:</div>
                                @foreach($msisdns as $msisdn)
                                    <span class="msisdn-chip">
                                        <i class="fas fa-sim-card text-muted" style="font-size:0.7rem;"></i>
                                        {{ trim($msisdn) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Info injeksi jika sudah dilakukan --}}
                    @if($trx->is_activated && $trx->injeksi_oleh)
                        <div class="injeksi-done-box">
                            <i class="fas fa-check-double me-2"></i>
                            Diinjeksi oleh <strong>{{ $trx->injeksi_oleh }}</strong>
                            pada <strong>{{ $trx->injeksi_at ? \Carbon\Carbon::parse($trx->injeksi_at)->format('d M Y H:i') : '-' }}</strong>
                            @if($trx->catatan_injeksi)
                                <br><i class="fas fa-comment-alt me-1"></i> Catatan: {{ $trx->catatan_injeksi }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Pagination --}}
        <div class="mt-3">
            @if(method_exists($transaksis, 'links'))
                {{ $transaksis->links('pagination::bootstrap-5') }}
            @endif
        </div>
    @endif

</div>

{{-- Modal Upload (SweetAlert2) --}}
@push('scripts')
<script>
function showBuktiInjeksi(url) {
    let isPdf = url.toLowerCase().endsWith('.pdf');
    if (isPdf) {
        Swal.fire({
            title: 'Bukti Injeksi Paket',
            html: `<iframe src="${url}" style="width:100%; height:500px; border:none; border-radius:8px;"></iframe>`,
            width: '800px',
            confirmButtonColor: '#bc0007',
            confirmButtonText: 'Tutup'
        });
    } else {
        Swal.fire({
            title: 'Bukti Injeksi Paket',
            imageUrl: url,
            imageAlt: 'Bukti Injeksi',
            width: '600px',
            confirmButtonColor: '#bc0007',
            confirmButtonText: 'Tutup'
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';

    document.querySelectorAll('.btn-open-upload').forEach(btn => {
        btn.addEventListener('click', function () {
            const id     = this.dataset.id;
            const nama   = this.dataset.nama;
            const paket  = this.dataset.paket;
            const jumlah = this.dataset.jumlah;
            const msisdns = this.dataset.msisdns;

            Swal.fire({
                title: '<i class="fas fa-upload me-2 text-danger"></i> Upload Bukti Injeksi',
                html: `
                    <div style="text-align:left; font-size:0.9rem;">
                        <div class="mb-3 p-3" style="background:#f8f9fa; border-radius:8px;">
                            <p class="mb-1 text-muted">Travel: <strong class="text-dark">${nama}</strong></p>
                            <p class="mb-1 text-muted">Paket: <strong class="text-dark">${paket}</strong></p>
                            <p class="mb-0 text-muted">Jumlah Jamaah: <strong class="text-danger">${jumlah} Nomor MSISDN</strong></p>
                        </div>
                        <div class="mb-2 p-2" style="background:#fff3cd; border-radius:8px; border:1px solid #ffc107; font-size:0.82rem;">
                            <i class="fas fa-info-circle text-warning me-1"></i>
                            <strong>Satu bukti injeksi</strong> akan diterapkan ke semua ${jumlah} MSISDN dalam grup ini.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">File Bukti Injeksi <span class="text-danger">*</span></label>
                            <input type="file" id="bukti_file_trv" accept=".jpg,.jpeg,.png,.pdf"
                                class="form-control" style="border-radius:8px;">
                            <small class="text-muted">Format: JPG, PNG, atau PDF. Maks. 8MB.</small>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Catatan (opsional)</label>
                            <textarea id="catatan_trv" class="form-control" rows="2"
                                placeholder="Contoh: Injeksi batch pagi, selesai 08:30"
                                style="border-radius:8px; resize:none;"></textarea>
                        </div>
                        <div id="preview-container-trv" class="mt-2 d-none">
                            <img id="img-preview-trv" src="" style="max-width:100%; border-radius:8px; border:1px solid #eee;">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#bc0007',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-upload me-1"></i> Upload & Aktifkan Semua',
                cancelButtonText: 'Batal',
                width: '560px',
                focusConfirm: false,
                preConfirm: () => {
                    const file = document.getElementById('bukti_file_trv').files[0];
                    if (!file) {
                        Swal.showValidationMessage('File bukti injeksi wajib dipilih!');
                        return false;
                    }
                    if (file.size > 8 * 1024 * 1024) {
                        Swal.showValidationMessage('Ukuran file terlalu besar. Maksimal 8MB.');
                        return false;
                    }
                    return {
                        file,
                        catatan: document.getElementById('catatan_trv').value
                    };
                },
                didOpen: () => {
                    document.getElementById('bukti_file_trv').addEventListener('change', function () {
                        const file = this.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = e => {
                                document.getElementById('img-preview-trv').src = e.target.result;
                                document.getElementById('preview-container-trv').classList.remove('d-none');
                            };
                            reader.readAsDataURL(file);
                        } else {
                            document.getElementById('preview-container-trv').classList.add('d-none');
                        }
                    });
                }
            }).then(result => {
                if (!result.isConfirmed) return;

                const formData = new FormData();
                formData.append('bukti_injeksi', result.value.file);
                formData.append('catatan_injeksi', result.value.catatan);
                formData.append('_token', csrfToken);

                Swal.fire({
                    title: 'Mengupload...',
                    html: '<div class="d-flex justify-content-center align-items-center py-3"><div class="spinner-border text-danger"></div><span class="ms-3">Memproses bukti injeksi...</span></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                fetch(`/programhaji/admin/travel-aktivasi/${id}/upload`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Injeksi Berhasil! ✅',
                            html: `
                                <p class="mb-3">${data.message}</p>
                                <div class="p-3 mb-3" style="background:#e8f5e9; border-radius:8px; text-align:left; font-size:0.9rem;">
                                    <div><i class="fas fa-user-shield me-2 text-success"></i>Oleh: <strong>${data.injeksi_oleh}</strong></div>
                                    <div class="mt-1"><i class="fas fa-clock me-2 text-success"></i>Waktu: <strong>${data.injeksi_at}</strong></div>
                                    <div class="mt-1"><i class="fas fa-users me-2 text-success"></i>Total MSISDN: <strong>${data.jumlah_msisdn} Nomor</strong></div>
                                </div>
                                <a href="${data.bukti_url}" target="_blank"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> Lihat Bukti Injeksi
                                </a>
                            `,
                            confirmButtonColor: '#bc0007',
                            confirmButtonText: 'Selesai',
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Terjadi kesalahan koneksi.', 'error'));
            });
        });
    });
});
</script>
@endpush
@endsection
