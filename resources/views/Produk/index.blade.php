<x-Supvis.SupvisLayouts>
    <!-- SweetAlert2 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <style>

        .dataTables_wrapper .dataTables_paginate .page-item .page-link {
            background-color: #23a0b0 !important;
            color: white !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 5px 10px !important;
            margin: 2px !important;
        }

        .dataTables_wrapper .dataTables_paginate .page-item .page-link:hover {
            background-color: #1b8190 !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background-color: #23a0b0 !important;
            color: white !important;
            font-weight: bold !important;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
            background-color: #b0b0b0 !important;
            color: #ffffff !important;
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    <div class="container mt-5">
        <h2 class="mb-4 text-center"><strong>Daftar Produk</strong></h2>
        <a href="{{ route('produk.create') }}" class="btn btn-success mb-3" style = "background-color : #23a0b0">Tambah
            Produk</a>

        @if ($produks->whereNull('deleted_at')->isEmpty())
            <div class="alert alert-warning text-center">Belum ada produk yang tersedia.</div>
            <br><br><br>
        @else
            <div class="row">
                @foreach ($produks->whereNull('deleted_at') as $produk)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold" style="color: black;">{{ $produk->produk_nama }}
                                </h5>
                                <p class="card-text"><strong>Harga:</strong> Rp
                                    {{ number_format($produk->produk_harga, 0, ',', '.') }}</p>
                                <p class="card-text"><strong>Diskon:</strong> Rp
                                    {{ number_format($produk->produk_diskon ?? 0, 0, ',', '.') }}</p>
                                <p class="card-text"><strong>Insentif:</strong> Rp
                                    {{ number_format($produk->produk_insentif, 0, ',', '.') }}</p>
                                <p class="card-text"><strong>Harga Final:</strong> Rp
                                    {{ number_format($produk->produk_harga_akhir, 0, ',', '.') }}</p>
                                <p class="card-text"><strong>Stok:</strong> {{ $produk->produk_stok }}</p>
                                <p class="card-text"><strong>Jumlah Terjual:</strong> <span
                                        class="badge bg-primary">{{ $produk->produk_terjual }}</span></p>
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="#" class="btn btn-info btn-sm btn-detail"
                                        data-id="{{ $produk->id }}">🔍 Detail</a>




                                    <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm">✏️
                                        Stok</a>
                                    <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                    </form>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            document.querySelectorAll('.delete-form').forEach(form => {
                                                form.addEventListener('submit', function(event) {
                                                    event.preventDefault();

                                                    Swal.fire({
                                                        title: "Apakah Anda yakin?",
                                                        text: "Produk ini akan dihapus secara permanen!",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#d33",
                                                        cancelButtonColor: "#3085d6",
                                                        confirmButtonText: "Ya, hapus!",
                                                        cancelButtonText: "Batal"
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            form.submit();
                                                        }
                                                    });
                                                });
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif


        {{-- Display Deleted Products --}}
        @if (Auth::user() && Auth::user()->is_superuser)
            <h2 class="mb-4 text-center"><strong>Produk Dihapus</strong></h2>

            @if ($produks->whereNotNull('deleted_at')->isEmpty())
                <div class="alert alert-warning text-center">Belum ada produk yang terhapus.</div>
            @else
                <div class="row">
                    @foreach ($produks->whereNotNull('deleted_at') as $produk)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold" style="color: black;">
                                        {{ $produk->produk_nama }}
                                    </h5>
                                    <p class="card-text"><strong>Harga:</strong> Rp
                                        {{ number_format($produk->produk_harga, 0, ',', '.') }}</p>
                                    <p class="card-text"><strong>Diskon:</strong> Rp
                                        {{ number_format($produk->produk_diskon ?? 0, 0, ',', '.') }}</p>
                                    <p class="card-text"><strong>Insentif:</strong> Rp
                                        {{ number_format($produk->produk_insentif, 0, ',', '.') }}</p>
                                    <p class="card-text"><strong>Harga Final:</strong> Rp
                                        {{ number_format($produk->produk_harga_akhir, 0, ',', '.') }}</p>
                                    <p class="card-text"><strong>Stok:</strong> {{ $produk->produk_stok }}</p>
                                    <p class="card-text"><strong>Jumlah Terjual:</strong> <span
                                            class="badge bg-primary">{{ $produk->produk_terjual }}</span></p>
                                    <p class="card-text"><strong>Tanggal Dihapus:</strong> <span
                                            class="badge bg-primary">{{ $produk->deleted_at->format('d M Y H:i') }}</span>
                                    </p>
                                    <div class="d-flex justify-content-between mt-3">

                                        <form action="{{ route('produk.restore', $produk->id) }}" method="POST"
                                            class="d-inline">

                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>

                                        </form>

                                        <!-- Gk usah pake Hapus Permanen karena butuh ambil harga untuk Riwayat Transaksi - billy

                                        <form action="{{ route('produk.force-delete', $produk->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus permanen produk ini?');">

                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus Permanen</button>

                                        </form> -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

    </div>

    <!-- Tabel Riwayat Penjualan -->
    <div class="container mt-4">
        <h3 class="mt-5 text-center"><strong>📊 Riwayat Penjualan</strong></h3>
    </div>
    @php
        $allHistory = collect();
        foreach ($produks as $produk) {
            $history = json_decode($produk->produk_terjual_history, true);
            if (is_array($history)) {
                $allHistory = $allHistory->merge($history);
            }
        }

        $groupedHistory = $allHistory
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d');
            })
            ->map(function ($items) {
                return [
                    'tanggal' => $items->first()['tanggal'] ?? '-',
                    'total_jumlah' => $items->sum('jumlah'),
                ];
            });

        $uniqueDates = $groupedHistory->keys();
    @endphp

    <!-- Filter Total Penjualan -->
    <div class ="container mt-4">
        <div class="mb-3">
            <label for="filterTanggal" class="form-label"><strong>📅 Filter Berdasarkan Tanggal:</strong></label>
            <select id="filterTanggal" class="form-select">
                <option value="all">Semua Tanggal</option>
                @foreach ($uniqueDates as $date)
                    <option value="{{ $date }}">{{ $date }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="container mt-4">
        <!-- Tabel Total Penjualan Per Tanggal -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead style="background-color: #23a0b0; color: white;">
                    <tr class="text-center">
                        <th style="width: 50%;">📅 Tanggal</th>
                        <th style="width: 50%;">📦 Total Jumlah Terjual</th>
                    </tr>
                </thead>
                <tbody id="totalPenjualanBody">
                    @foreach ($groupedHistory as $entry)
                        <tr class="text-center" data-tanggal="{{ $entry['tanggal'] }}">
                            <td><strong>{{ \Carbon\Carbon::parse($entry['tanggal'])->format('d M Y') }}</strong></td>
                            <td><span class="badge bg-success fs-6">{{ $entry['total_jumlah'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tabel Detail Riwayat  -->
        <h4 class="mt-5 text-center"><strong>📋 Detail Riwayat </strong></h4>
        <div class="table-responsive">
            <table id="detilHistoryTable" class="table table-striped table-hover">
                <thead style="background-color: #23a0b0; color: white;">
                    <tr class="text-center">
                        <th>📅 Tanggal & Waktu</th>
                        <th>📦 Jumlah</th>
                        <th>🛍️ Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allHistory as $entry)
                        <tr class="text-center">
                            <td>{{ $entry['tanggal'] }}</td>
                            <td><span class="badge bg-primary">{{ $entry['jumlah'] ?? '-' }}</span></td>
                            <td>{{ $entry['produk_nama'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    </div>
    <script>
        document.getElementById('filterTanggal').addEventListener('change', function() {
            let selectedDate = this.value;
            let totalRows = document.querySelectorAll('#totalPenjualanBody tr');
            totalRows.forEach(row => {
                let rowDate = row.getAttribute('data-tanggal');
                row.style.display = (selectedDate === "all" || rowDate.startsWith(selectedDate)) ? "" : "none";
            });

            // Filter "Detail Riwayat Pengambilan" table
            let detailRows = document.querySelectorAll('#detilHistoryTable tbody tr');
            detailRows.forEach(row => {
                let rowDate = row.cells[0].textContent.trim();
                row.style.display = (selectedDate === "all" || rowDate.startsWith(selectedDate)) ? "" : "none";
            });

        });
        $(document).ready(function() {
            $(document).off('click', '.btn-detail').on('click', '.btn-detail', function() {
                let produkId = $(this).data('id');

                $.ajax({
                    url: "/programhaji/produk/" + produkId,
                    type: "GET",
                    success: function(response) {
                        Swal.fire({
                            title: response.produk_nama,
                            html: `
                        <div style="text-align:left;">
                            <p><strong>Harga:</strong> Rp ${new Intl.NumberFormat().format(response.produk_harga)}</p>
                            <p><strong>Diskon:</strong> ${response.produk_diskon}</p>
                            <p><strong>Stok:</strong> ${response.produk_stok}</p>
                            <p><strong>Detail:</strong> ${response.produk_detail ?? 'Tidak ada detail'}</p>
                            <p><strong>Insentif:</strong> Rp ${new Intl.NumberFormat().format(response.produk_insentif)}</p>
                            <p><strong>Merchandise:</strong> ${response.merchandises.length > 0 ? response.merchandises.join(', ') : 'Tidak ada merchandise terkait'}</p>
                        </div>
                    `,
                            icon: 'info',
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#23a0b0'
                        });
                    }
                });
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#totalPenjualanTable, #detailPenjualanTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthMenu": [5, 10, 25, 50, 100],
                "autoWidth": false,
                "language": {
                    "search": '<label class="custom-search" style="margin-left: 10px; margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">' +
                        '<i class="fas fa-search"></i> Cari:',
                    "lengthMenu": '<div class="d-flex align-items-center gap-1 mb-3">' +
                        '<span class="me-1">Tampilkan</span>' +
                        '<select class="form-select form-select-sm w-auto" style="min-width: 60px;">' +
                        '<option value="5">5</option>' +
                        '<option value="10">10</option>' +
                        '<option value="25">25</option>' +
                        '<option value="50">50</option>' +
                        '<option value="100">100</option>' +
                        '</select>' +
                        '<span>data per halaman</span>' +
                        '</div>',
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data tersedia",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>
</x-Supvis.SupvisLayouts>

