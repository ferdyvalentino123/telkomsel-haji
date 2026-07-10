@extends("admin.layout")
@section("title", "Daftar Transaksi")
@section("content")

<!-- Page Title -->
<div class="mb-4">
  <h1 class="page-title"><i class="fas fa-credit-card"></i> Daftar Transaksi</h1>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
  <div class="col-lg-4 col-md-6">
    <div class="stat-card">
      <div class="stat-icon stat-icon-2">
        <i class="fas fa-receipt"></i>
      </div>
      <div class="stat-label">Total Transaksi {{ request('date') ? '(Filtered)' : '' }}</div>
      <div class="stat-value">{{ $totalTransaksi }}</div>
    </div>
  </div>
  <div class="col-lg-4 col-md-6">
    <div class="stat-card">
      <div class="stat-icon stat-icon-3">
        <i class="fas fa-dollar-sign"></i>
      </div>
      <div class="stat-label">Total Pendapatan {{ request('date') ? '(Filtered)' : '' }}</div>
      <div class="stat-value">Rp {{ number_format($totalPendapatan, 0, ",", ".") }}</div>
    </div>
  </div>
  <div class="col-lg-4 col-md-6">
    <div class="stat-card">
      <div class="stat-icon stat-icon-1">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="stat-label">Sudah Dibayar {{ request('date') ? '(Filtered)' : '' }}</div>
      <div class="stat-value">{{ $totalDibayar }}</div>
    </div>
  </div>
</div>

<!-- Transactions Table -->
<div class="row">
  <div class="col-12">
    <div class="content-card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 w-100">
          <h5 class="mb-0"><i class="fas fa-list me-2" style="color: #bc0007;"></i>Daftar Semua Transaksi</h5>
          <div class="d-flex gap-2 flex-wrap align-items-center">
            {{-- Filter & Export Group --}}
            <div class="d-flex gap-2 align-items-center flex-wrap">
              <form method="GET" class="d-flex gap-2 align-items-center flex-wrap" id="filterForm">
                <div class="input-group input-group-sm" style="width: 250px;">
                  <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                  <input type="text" name="search" class="form-control border-start-0" placeholder="Cari Pelanggan / Sales..." value="{{ request('search') }}">
                </div>

                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" style="width: 150px;">
                
                <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                  <i class="fas fa-filter" style="font-size: 0.75rem;"></i>
                  <span>Filter</span>
                </button>
                
                @if(request('date') || request('search'))
                  <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" title="Reset filter" style="width: 31px; height: 31px;">
                    <i class="fas fa-times"></i>
                  </a>
                @endif
              </form>
              
              <div class="vr mx-1 d-none d-md-block" style="height: 20px; opacity: 0.2;"></div>

              {{-- Tombol Export Excel — meneruskan filter tanggal yang sedang aktif --}}
              <a href="{{ route('admin.transaksi.export', request()->only('date')) }}"
                 class="btn btn-sm btn-success d-flex align-items-center gap-2 px-3"
                 title="{{ request('date') ? 'Export data ' . request('date') : 'Export semua transaksi' }}">
                <i class="fas fa-file-excel"></i>
                <span class="fw-bold">Export Excel</span>
                @if(request('date'))
                  <span class="badge bg-white text-success ms-1" style="font-size:0.65rem; padding: 2px 6px;">
                    {{ \Carbon\Carbon::parse(request('date'))->translatedFormat('d M Y') }}
                  </span>
                @endif
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>ID Transaksi/Grup</th>
              <th>Pelanggan / Travel</th>
              <th>Info MSISDN</th>
              <th>Sales</th>
              <th>Jenis Paket</th>
              <th>Total Bayar</th>
              <th>Tanggal</th>
              <th>Status Bayar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transaksi ?? [] as $t)
              @php
                  $isTravel = $t->supervisor && $t->supervisor->role === 'travel';
                  $pelangganName = $isTravel ? $t->supervisor->name . ' <span class="badge bg-primary ms-1" style="font-size:0.6rem;">B2B Travel</span>' : ($t->nama_pelanggan ?: '-');
                  $namaSales = $isTravel ? '-' : ($t->nama_sales ?: '-');
                  $msisdnInfo = $isTravel 
                      ? '<span class="badge bg-secondary"><i class="fas fa-users me-1"></i>' . ($t->total_msisdn ?? 1) . ' Nomor</span>'
                      : '<span class="fw-semibold text-dark">' . ($t->telepon_pelanggan ?: ($t->pelanggan->phone ?? '-')) . '</span>';
                  
                  // Gunakan snap_token untuk search internal, tapi gunakan id_transaksi (dibersihkan ujungnya) untuk tampilan
                  $searchToken = $t->snap_token ?: $t->id_transaksi;
                  $displayId = $isTravel ? preg_replace('/-\d+$/', '', $t->id_transaksi) : $t->id_transaksi;
                  $totalHarga = $t->akumulasi_harga ?? $t->total_harga ?? 0;
              @endphp
              <tr>
                <td><strong>{{ $loop->iteration }}</strong></td>
                <td><span class="fw-bold text-dark">#{{ $displayId }}</span></td>
                <td>{!! $pelangganName !!}</td>
                <td>{!! $msisdnInfo !!}</td>
                <td><span>{{ $namaSales }}</span></td>
                <td>
                  @php
                    $namaPaket = $t->produk ? $t->produk->produk_nama : ($t->jenis_paket ?: '-');
                  @endphp
                  <span class="text-dark" style="font-size:0.8rem; white-space:normal; max-width:160px; display:inline-block;">
                    <i class="fas fa-sim-card text-danger me-1"></i>{{ $namaPaket }}
                  </span>
                </td>
                <td>
                  <span class="badge bg-success">Rp {{ number_format($totalHarga, 0, ",", ".") }}</span>
                </td>
                <td>{{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->format("d M Y H:i") : "-" }}</td>
                <td>
                  @if($t->status == 'lunas' || $t->status == 'success' || $t->is_paid)
                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Lunas</span>
                  @elseif($t->status == 'pending' || !$t->status)
                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending </span>
                  @else
                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> {{ ucfirst($t->status) }}</span>
                  @endif
                </td>
                <td>
                  <div class="btn-group btn-group-sm" role="group">
                    <button type="button" onclick="viewTransaksiDetail('{{ $displayId }}', '{{ addslashes(strip_tags($pelangganName)) }}', 'Rp {{ number_format($totalHarga, 0, ',', '.') }}', '{{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y H:i') : '' }}', '{{ ($t->is_paid || in_array($t->status, ['lunas', 'success'])) ? 'Dibayar' : 'Pending' }}', '{{ strip_tags($msisdnInfo) }}', '{{ $namaSales }}', '{{ addslashes($t->produk ? $t->produk->produk_nama : ($t->jenis_paket ?: '-')) }}', '{{ $isTravel ? ($t->total_msisdn . " Nomor") : ($t->nomor_injeksi ?: "-") }}')" class="btn btn-outline-info" title="Detail">
                      <i class="fas fa-eye"></i>
                    </button>
                    
                    @if(($t->status == 'lunas' || $t->status == 'success' || $t->is_paid))
                      @if($isTravel)
                        @if(!$t->is_activated)
                          <a href="{{ route('admin.travel-aktivasi.index', ['search' => $searchToken]) }}" class="btn btn-warning" title="Perlu Injeksi Travel">
                            <i class="fas fa-plane-arrival"></i> Injeksi Travel
                          </a>
                        @else
                          <a href="{{ route('admin.travel-aktivasi.index', ['search' => $searchToken]) }}" class="btn btn-success" title="Sudah Aktif (Lihat Detail)">
                            <i class="fas fa-check-double"></i> Travel Aktif
                          </a>
                        @endif
                      @else
                        @if(!$t->is_activated)
                          <button type="button" onclick="prosesAktivasi('{{ route('admin.transaksi.aktivasi', $t->id) }}', '{{ addslashes($t->nama_pelanggan ?? '') }}', '{{ $t->nomor_injeksi ?: $t->telepon_pelanggan }}')" class="btn btn-warning" title="Aktivasi Paket">
                            <i class="fas fa-bolt"></i> Aktivasi
                          </button>
                        @else
                          <button type="button" class="btn btn-success disabled" title="Sudah Aktif">
                            <i class="fas fa-check-double"></i> Aktif
                          </button>
                        @endif
                      @endif
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center py-4">
                  <em class="text-muted"><i class="fas fa-inbox"></i> Tidak ada transaksi</em>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function viewTransaksiDetail(id, nama, total, tgl, status, roaming, sales, produk, injeksi) {
    let badge = status === 'Dibayar' 
        ? '<span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle"></i> Lunas</span>' 
        : '<span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-clock"></i> Pending</span>';

    Swal.fire({
        title: 'Pesanan #' + id,
        html: `
            <div style="text-align: left; font-size: 0.95rem; margin-top: 10px;">
                <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                    ${badge}
                    <span class="text-muted fw-bold"><i class="far fa-calendar-alt"></i> ${tgl}</span>
                </div>
                <div class="bg-light p-3 rounded-4 border mb-3">
                    <div class="text-muted mb-1 small">Paket yang dibeli:</div>
                    <div class="fw-bold text-dark fs-6"><i class="fas fa-box-open text-danger me-2"></i> ${produk}</div>
                </div>
                <div class="bg-white p-3 rounded-4 border">
                    <table class="table table-borderless table-sm mb-0">
                        <tr><td width="40%" class="text-muted">Pelanggan</td><td><strong class="text-dark">${nama || '-'}</strong></td></tr>
                        <tr><td class="text-muted">Nomor Telepon</td><td><strong class="text-dark">${roaming}</strong></td></tr>
                        <tr><td class="text-muted">Sales</td><td><span>${sales || '-'}</span></td></tr>
                        <tr><td class="text-muted">Total Bayar</td><td><strong class="text-danger fs-5">${total}</strong></td></tr>
                        <tr><td class="text-muted">Nomor Injeksi</td><td><strong style="color: #00bc67ff; font-size: 1.1rem;">${injeksi}</strong></td></tr>
                    </table>
                </div>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#bc0007',
        customClass: {
            popup: 'rounded-4 shadow-lg'
        }
    });
}

function prosesAktivasi(url, nama, nomor) {
    Swal.fire({
        title: 'Aktivasi Paket',
        html: `
            <div class="text-start mb-3">
                <p class="mb-1 text-muted">Pelanggan: <strong>${nama}</strong></p>
                <p class="mb-0 text-muted">No. Injeksi: <strong>${nomor}</strong></p>
            </div>
            <div class="alert alert-info py-2 small">
                <i class="fas fa-info-circle"></i> Silakan upload screenshot bukti injeksi dari sistem Telkomsel untuk menyelesaikan transaksi.
            </div>
        `,
        input: 'file',
        inputAttributes: {
            'accept': 'image/*,application/pdf',
            'aria-label': 'Upload bukti injeksi'
        },
        showCancelButton: true,
        confirmButtonText: 'Upload & Aktifkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#bc0007',
        showLoaderOnConfirm: true,
        preConfirm: (file) => {
            if (!file) {
                Swal.showValidationMessage('Anda harus memilih file bukti injeksi!');
                return false;
            }
            
            let formData = new FormData();
            formData.append('bukti_injeksi', file);
            formData.append('_token', '{{ csrf_token() }}');

            return fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json().catch(() => null);
                if (!response.ok) {
                    const errorMsg = data && data.message ? data.message : response.statusText;
                    throw new Error(errorMsg);
                }
                return data;
            })
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Paket telah diaktifkan dan bukti telah diunggah.',
                confirmButtonColor: '#bc0007'
            }).then(() => {
                location.reload();
            });
        }
    });
}
</script>



@endsection

