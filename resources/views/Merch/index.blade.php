<x-Supvis.SupvisLayouts>
    <!-- SweetAlert2 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <div class="container mt-5">
        <h2 class="mb-4 text-center"><strong>Daftar Merchandise</strong></h2>
        <a href="{{ route('merch.create') }}" class="btn btn-success mb-3" style="background-color: #23a0b0;">Tambah Merchandise</a>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @if ($merchandises->whereNull('deleted_at')->isEmpty())
            <div class="alert alert-warning text-center">Belum ada merchandise yang tersedia.</div>
            <br><br><br>
        @else
            <div class="row">
                @foreach ($merchandises->whereNull('deleted_at') as $merchandise)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold" style="color: black;">{{ $merchandise->merch_nama }}</h5>
                                <p class="card-text"><strong>Deskripsi:</strong> {{ $merchandise->merch_detail }}</p>
                                <p class="card-text"><strong>Stok:</strong> {{ $merchandise->merch_stok }}</p>
                                <p class="card-text"><strong>Jumlah Terambil:</strong> <span class="badge bg-primary">{{ $merchandise->merch_terambil }}</span></p>
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="#" class="btn btn-info btn-sm btn-detail" data-id="{{ $merchandise->id }}">🔍 Detail</a>
                                    <a href="{{ route('merch.edit', $merchandise->id) }}" class="btn btn-warning btn-sm">✏️ Stok</a>
                                    <form action="{{ route('merch.destroy', $merchandise->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                    </form>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            document.querySelectorAll('.delete-form').forEach(form => {
                                                form.addEventListener('submit', function (event) {
                                                    event.preventDefault();

                                                    Swal.fire({
                                                        title: "Apakah Anda yakin?",
                                                        text: "Merchandise ini akan dihapus secara permanen!",
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
                                        $(document).ready(function() {
                                            $(document).off('click', '.btn-detail').on('click', '.btn-detail', function() {
                                                let merchId = $(this).data('id');

                                                $.ajax({
                                                    url: "/programhaji/merch/" + merchId,
                                                    type: "GET",
                                                    success: function(response) {
                                                        Swal.fire({
                                                            title: response.merch_nama,
                                                            html: `
                                                        <div style="text-align:left;">
                                                            <p><strong>Deskripsi:</strong> ${response.merch_detail}</p>
                                                            <p><strong>Stok:</strong> ${response.merch_stok}</p>
                                                            <p><strong>Jumlah Terambil:</strong> ${response.merch_terambil}</p>
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
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Display Deleted Merchs --}}
        <h2 class="mb-4 text-center"><strong>Merchandise Terhapus</strong></h2>
        @if ($merchandises->whereNotNull('deleted_at')->isEmpty())
            <div class="alert alert-warning text-center">Belum ada merch yang terhapus.</div>
        @else
            <div class="row">
                @foreach ($merchandises->whereNotNull('deleted_at') as $merch)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold" style="color: black;">{{ $merch->merch_nama }}
                                </h5>
                                <p class="card-text"><strong>Deskripsi:</strong> {{ $merch->merch_detail }}</p>
                                <p class="card-text"><strong>Stok:</strong> {{ $merch->merch_stok }}</p>
                                <p class="card-text"><strong>Jumlah Terambil:</strong> <span class="badge bg-primary">{{ $merch->merch_terambil }}</span></p>
                                <div class="d-flex justify-content-between mt-3">

                                    <form action="{{ route('merch.restore', $merch->id) }}" method="POST"
                                        class="d-inline">

                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Restore</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif


        <h3 class="mt-5 text-center"><strong>📊 Riwayat Pengambilan Merchandise</strong></h3>

        @php
            $allHistory = collect();
            foreach ($merchandises as $merchandise) {
                $history = json_decode($merchandise->merch_terambil_history, true);
                if (is_array($history)) {
                    $allHistory = $allHistory->merge($history);
                }
            }

            $groupedHistory = $allHistory->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d');
            })->map(function ($items) {
                return [
                    'tanggal' => $items->first()['tanggal'] ?? '-',
                    'total_jumlah' => $items->sum('jumlah')
                ];
            });
            $uniqueDates = $groupedHistory->keys();
        @endphp

        <!-- Filter Total Pengambilan -->
        <div class="mb-3">
            <label for="filterTanggal" class="form-label"><strong>📅 Filter Berdasarkan Tanggal:</strong></label>
            <select id="filterTanggal" class="form-select">
                <option value="all">Semua Tanggal</option>
                @foreach ($uniqueDates as $date)
                    <option value="{{ $date }}">{{ $date }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tabel Total Pengambilan Per Tanggal -->
        <div class="table-responsive">
            <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th style="width: 50%;">📅 Tanggal</th>
                        <th style="width: 50%;">📦 Total Merchandise Terambil</th>
                    </tr>
                </thead>
                <tbody id="totalPengambilanBody">
                    @foreach ($groupedHistory as $entry)
                        <tr class="text-center" data-tanggal="{{ $entry['tanggal'] }}">
                            <td><strong>{{ Carbon\Carbon::parse($entry['tanggal'])->format('Y-m-d') }}</strong></td>
                            <td><span class="badge bg-success fs-6">{{ $entry['total_jumlah'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tabel Detail Riwayat -->
        <h4 class="mt-5 text-center"><strong>📋 Detail Riwayat </strong></h4>
        <div class="table-responsive">
            <table id="detilHistoryTable" class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>📅 Tanggal & Waktu</th>
                        <th>📦 Jumlah</th>
                        <th>🎁 Merchandise</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allHistory as $entry)
                        <tr class="text-center">
                            <td>{{ $entry['tanggal'] }}</td>
                            <td><span class="badge bg-primary">{{ $entry['jumlah'] ?? '-' }}</span></td>
                            <td>{{ $entry['merch_nama'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('filterTanggal').addEventListener('change', function () {
            let selectedDate = this.value;
            
            // Filter "Total Merchandise Terambil" table
            let totalRows = document.querySelectorAll('#totalPengambilanBody tr');
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

    </script>
</x-Supvis.SupvisLayouts>

