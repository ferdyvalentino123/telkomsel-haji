<x-Kasir.KasirLayouts><main class="content"><div class="container-fluid p-0">
    
    
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>

        body {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
        #salesTable th,#submit-btn, #update-btn,#transaksiTable th,.sales-setor th.text-center {
            background-color: #23a0b0;

}
#submit-btn{
background-color : #23a0b0;
}
/* Perbaikan pagination agar horizontal */
.dataTables_paginate .pagination, .dataTables_paginate ul {
    display: flex !important;
    flex-wrap: nowrap !important;
    justify-content: flex-end !important;
    margin: 0 !important;
    list-style: none !important;
    list-style-type: none !important;
    padding-left: 0 !important;
}

.dataTables_wrapper .dataTables_paginate .page-item .page-link {
    background-color: #23a0b0 !important;
    color: white !important;
    border: none !important;
    border-radius: 5px !important;
    padding: 6px 12px !important;
    margin: 0 2px !important;
    white-space: nowrap !important;
}

/* Hover pada tombol pagination */
.dataTables_wrapper .dataTables_paginate .page-item .page-link:hover {
    background-color: #1b8190 !important;
    color: white !important;
}

/* Warna tombol aktif */
.dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
    background-color: #23a0b0 !important;
    color: white !important;
    font-weight: bold !important;
    box-shadow: none !important;
}

/* Warna tombol disabled */
.dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
    background-color: #b0b0b0 !important;
    color: #ffffff !important;
    opacity: 0.6;
    cursor: not-allowed;
}


        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            h2 {
                font-size: 1.5rem;
                text-align: center;
            }

            table {
                font-size: 0.9rem;
            }

            .btn {
                width: 100%;
                font-size: 1rem;
            }

            select {
                font-size: 1rem;
            }

            th,
            td {
                padding: 8px;
            }

            /* Untuk menyembunyikan kolom jika terlalu panjang di HP */
            .hide-on-mobile {
                display: none;
            }

            table {
                table-layout: fixed;
                width: 100%;
            }

            th,
            td {
                width: 50%;
                /* Bagi dua kolom dengan proporsi yang sama */
            }

        }

        @media (max-width: 480px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th,
            td {
                font-size: 0.8rem;
                padding: 6px;
            }

            h2 {
                font-size: 1.2rem;
            }

            table {
                table-layout: fixed;
                width: 100%;
            }

            th,
            td {
                width: 50%;
                /* Bagi dua kolom dengan proporsi yang sama */
            }

        }
    </style>

    <body>
        <div class="container mt-5">
            <h2>Sales Checklist</h2>
            <div class="container mt-3">
                <p>Pilih nama sales yang sudah setoran</p>

                @if ($sales->isNotEmpty())
                    <table id="salesTable" class="table">
                        <thead>
                            <tr>
                                <th>Setoran Aktif</th>
                                <th>Nama Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $index => $salesperson)
                                <tr>
                                    <td>
                                        <input type="checkbox" id="sales{{ $salesperson->id }}"
                                            value="{{ $salesperson->id }}" class="setoran-checkbox"
                                            data-sales-id="{{ $salesperson->id }}"
                                            {{ $salesperson->is_setoran == 1 ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $salesperson->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button id="submit-btn" class="btn btn-primary mt-3">Aktifkan Sales</button>
                @else
                    <p>Tidak ada sales yang tersedia.</p>
                @endif
                <div id="result" style="display: none;">
                    <h2>Checklist Result</h2>
                    <p id="checked-names">Tidak ada sales yang dichecklist.</p>
                </div>
            </div>

            <h2 class="mt-5">Data History Setoran Sales</h2>
            <div class="container mt-3">
                <label for="salesSelect">Pilih Sales:</label>
                <select id="salesSelect" class="form-control">
                    <option value="">-- Pilih Sales --</option>
                    @foreach ($transaksiBelumSetor as $salesName => $transaksiGroup)
                        <option value="{{ \Illuminate\Support\Str::slug($salesName) }}">{{ $salesName }}</option>
                    @endforeach
                </select>
                <form id="update-form" action="/update-setoran-status" method="POST">
                    @csrf

                    @forelse ($transaksiBelumSetor as $salesName => $transaksiGroup)
                        <table class="table table-bordered mt-4 sales-table"
                            data-sales="{{ \Illuminate\Support\Str::slug($salesName) }}">
                            <thead>
                                <!-- Nama Sales sebagai judul dengan colspan -->
                                <tr class="sales-setor">
                                    <th colspan="5" class="text-center">{{ $salesName }}</th>
                                </tr>
                                <tr>
                                    <th>Pilih</th>
                                    <th>ID Transaksi</th>
                                    <th>Tanggal Setoran</th>
                                    <th>Total Harga</th>
                                    <th>Total Insentif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotalHarga = 0;
                                    $subtotalInsentif = 0;
                                @endphp
                                @foreach ($transaksiGroup as $transaksi)
                                    @php
                                    $history = $transaksi->history_setoran;
                                    @endphp
                                    @if (is_array($history))
                                        @foreach ($history as $entry)
                                            @php
                                                $harga = isset($entry['total_harga'])
                                                    ? (float) str_replace('.', '', $entry['total_harga'])
                                                    : 0;
                                                $insentif = isset($entry['total_insentif'])
                                                    ? (float) str_replace('.', '', $entry['total_insentif'])
                                                    : 0;

                                                $subtotalHarga += $harga;
                                                $subtotalInsentif += $insentif;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="setoran_data[]"
                                                        value="{{ $transaksi->id_transaksi }}">
                                                </td>
                                                <td>{{ $transaksi->id_transaksi }}</td>
                                                <td>{{ $entry['tanggal'] }}</td>
                                                <td>{{ number_format($harga, 0, ',', '.') }}</td>
                                                <td>{{ number_format($insentif, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    <td colspan="3"><strong>Total</strong></td>
                                    <td>{{ number_format($subtotalHarga, 0, ',', '.') }}</td>
                                    <td>{{ number_format($subtotalInsentif, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    @empty
                        <p>Tidak ada data history setoran yang tersedia.</p>
                    @endforelse

                    <button id="update-btn" type="submit" class="btn btn-primary mt-3">Perbarui Status Setoran</button>
                </form>
            </div>



            <h2 class="mt-5">Data Transaksi yang Sudah Disetor Sales</h2>
            <div class="container mt-3">
                <table id="transaksiTable" class="table table-bordered display">
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th>ID Transaksi</th>
                            <th>Nama Sales</th>
                            <th>Tanggal Setoran</th>
                            <th>Total Harga</th>
                            <th>Total Insentif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksiSudahSetor as $transaksi)
                            @php
                                $history = $transaksi->history_setoran;
                            @endphp
                            @if (is_array($history))
                                @foreach ($history as $entry)
                                    @php
                                        // Ambil harga dan insentif dari produk terkait
                                        $harga = $transaksi->produk->produk_harga_akhir ?? 0;
                                        $insentif = $transaksi->produk->produk_insentif ?? 0;
                                    @endphp
                                    <tr>
                                        <td><input type="checkbox" disabled checked></td>
                                        <td>{{ $transaksi->id_transaksi }}</td>
                                        <td>{{ $entry['nama_sales'] }}</td>
                                        <td>{{ $entry['tanggal'] }}</td>
                                        <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($insentif, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="6">Tidak ada transaksi yang sudah disetor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
            

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.getElementById('submit-btn').addEventListener('click', function() {
                    const checkboxes = document.querySelectorAll('.setoran-checkbox');
                    const salesStatus = [];

                    checkboxes.forEach(checkbox => {
                        salesStatus.push({
                            id: checkbox.dataset.salesId,
                            is_setoran: checkbox.checked ? 1 : 0
                        });
                    });

                    console.log("Data yang dikirim ke backend:", salesStatus); // Debugging

                    fetch("{{ route('kasir.update.setoran.sales') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                sales: salesStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Response dari server:", data); // Debugging
                            alert(data.success ? 'Setoran berhasil diperbarui!' : 'Gagal memperbarui setoran.');
                        })
                        .catch(error => console.error('Error:', error));
                });

                document.getElementById('update-btn').addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah form terkirim langsung
                    const checkboxes = document.querySelectorAll('input[name="setoran_data[]"]:checked');

                    if (checkboxes.length === 0) {
                        Swal.fire({
                            title: "Peringatan!",
                            text: "Silakan pilih setoran terlebih dahulu.",
                            icon: "warning",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK"
                        });
                        return;
                    }
                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Pastikan data yang dipilih sudah benar!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Perbarui!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('update-form').submit(); // Kirim form jika dikonfirmasi
                        }
                    });
                });

                // Fungsi untuk menginisialisasi DataTables
                function initializeDataTable(selector) {
                    $(selector).DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": true,
                        "lengthMenu": [5, 10, 25, 50, 100],
                        "language": {
                            "lengthMenu": "Tampilkan _MENU_ data per halaman",
                            "zeroRecords": "Tidak ada data ditemukan",
                            "info": "Menampilkan _PAGE_ dari _PAGES_ halaman",
                            "infoEmpty": "Tidak ada data tersedia",
                            "infoFiltered": "(disaring dari _MAX_ total data)",
                            "search": "Cari:",
                            "paginate": {
                                "first": "Pertama",
                                "last": "Terakhir",
                                "next": "Selanjutnya",
                                "previous": "Sebelumnya"
                            }
                        }
                    });
                }

                $(document).ready(function() {
                    // Inisialisasi DataTables untuk tabel Sales Checklist
                    initializeDataTable('#salesTable');

                    // Inisialisasi DataTables untuk tabel Transaksi
                    initializeDataTable('#transaksiTable');

                    // Inisialisasi DataTables untuk semua tabel history setoran (id yang dimulai dengan "table-")
                    $('table[id^="table-"]').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": true,
                        "lengthMenu": [5, 10, 25, 50, 100],
                        "language": {
                            "lengthMenu": "Tampilkan _MENU_ data per halaman",
                            "zeroRecords": "Tidak ada data ditemukan",
                            "info": "Menampilkan _PAGE_ dari _PAGES_ halaman",
                            "infoEmpty": "Tidak ada data tersedia",
                            "infoFiltered": "(disaring dari _MAX_ total data)",
                            "search": "Cari:",
                            "paginate": {
                                "first": "Pertama",
                                "last": "Terakhir",
                                "next": "Selanjutnya",
                                "previous": "Sebelumnya"
                            }
                        },
                        "columnDefs": [{
                                "orderable": false,
                                "targets": 0
                            } // Nonaktifkan sorting pada kolom "Pilih"
                        ]
                    });
                });

                // Tombol aktivasi sales
                document.getElementById("submit-btn").addEventListener("click", function() {
                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Sales akan diaktifkan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, aktifkan!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire("Berhasil!", "Sales telah diaktifkan.", "success");
                            // Tambahkan aksi untuk mengaktifkan sales di sini
                        }
                    });
                });
                document.getElementById('salesSelect').addEventListener('change', function() {
                    let selectedSales = this.value;

                    // Sembunyikan semua tabel terlebih dahulu
                    document.querySelectorAll('.sales-table').forEach(table => {
                        table.style.display = "none";
                    });

                    // Jika ada sales yang dipilih, tampilkan tabel yang sesuai
                    if (selectedSales !== "") {
                        document.querySelector(`.sales-table[data-sales="${selectedSales}"]`).style.display = "table";
                    }
                });
            </script>

            
        </div>
    </main>
</x-Kasir.KasirLayouts>


