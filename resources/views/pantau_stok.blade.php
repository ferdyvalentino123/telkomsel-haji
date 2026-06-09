<x-supvis.layouts>

<link href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
    
<div class="container">
    <h2 class="text-center mt-5 mb-5"><b>Riwayat Perubahan Stok Produk</b></h2>

    <table id="produk-stock-table" class="table table-bordered">
        <thead>
            <tr class="text-center" style="background-color:#23a0b0">
                <th style="background-color:#23a0b0">No</th>
                <th>Produk</th>
                <th>Perubahan Stok</th>
                <th>Stok Sebelum</th>
                <th>Stok Sesudah</th>
                <th>Aksi</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productStockHistories as $history)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $history->product->produk_nama ?? "Tidak ditemukan" }}
                    </td>
                    <td class="{{ $history->change_amount > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $history->change_amount }}
                    </td>
                    <td>{{ $history->previous_stock ?? 'N/A' }}</td>
                    <td>{{ $history->current_stock ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $history->action === 'add' ? 'bg-success' : 'bg-warning' }}">
                            {{ $history->action === 'add' ? 'Penambahan' : 'Penggantian' }}
                        </span>
                    </td>
                    <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="container">
    <h2 class="text-center mt-5 mb-5"><b>Riwayat Perubahan Stok Merchandise</b></h2>

    <table id="merch-stock-table" class="table table-bordered">
        <thead>
            <tr class="text-center" style="background-color:#23a0b0">
                <th style="background-color:#23a0b0">No</th>
                <th>Merchandise</th>
                <th>Perubahan Stok</th>
                <th>Stok Sebelum</th>
                <th>Stok Sesudah</th>
                <th>Aksi</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($merchandiseStockHistories as $history)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $history->merchandise->merch_nama ?? "Tidak ditemukan" }}
                </td>
                <td class="{{ $history->change_amount > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $history->change_amount }}
                </td>
                <td>{{ $history->previous_stock ?? 'N/A' }}</td>
                <td>{{ $history->current_stock ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $history->action === 'add' ? 'bg-success' : 'bg-warning' }}">
                        {{ $history->action === 'add' ? 'Penambahan' : 'Penggantian' }}
                    </span>
                </td>
                <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
            </tr>                
            @endforeach
        </tbody>
    </table>
</div>

{{-- Script DataTables --}}
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#produk-stock-table', {
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            order: [[0, 'desc']], // Sort by ID column descending
            columnDefs: [
                { 
                    targets: 5, // Aksi column
                    orderable: false // Disable sorting for Aksi column
                }
            ]
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#merch-stock-table', {
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            order: [[0, 'desc']], // Sort by ID column descending
            columnDefs: [
                { 
                    targets: 5, // Aksi column
                    orderable: false // Disable sorting for Aksi column
                }
            ]
        });
    });
</script>
</x-supvis.layouts>
