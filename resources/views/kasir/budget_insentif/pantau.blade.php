
<x-kasir.layouts><main class="content"><div class="container-fluid p-0">
<link href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
    <div class="container">
        <h2 class="text-center mt-5 mb-5"><b>Riwayat Perubahan Budget</b></h2>

        <div class="row my-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header" style="color: black;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total Budget</span>
                            <a href="{{ route('kasir.budget_insentif.index') }}" class="btn btn-sm btn-light">Edit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 style="color: black;" class="card-title">{{ number_format($totalBudget, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header" style="color: black;">Total Insentif Digunakan</div>
                    <div class="card-body">
                        <h5 style="color: black;" class="card-title">{{ number_format($totalInsentif, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header" style="color: black;">Sisa Budget</div>
                    <div class="card-body">
                        <h5 style="color: black;" class="card-title">{{ number_format($sisaBudget, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <table id="budget-table" class="table table-bordered">
            <thead style="background-color: #23a0b0;" >
                <tr class="text-center text-white">
                    <th class="text-white">ID</th>
                    <th class="text-white">Perubahan Budget</th>
                    <th class="text-white">Budget Sebelum</th>
                    <th class="text-white">Budget Sesudah</th>
                    <th class="text-white">Aksi</th>
                    <th class="text-white">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($budgetHistories as $history)
                    <tr class="text-center">
                        <td>{{ $history->id }}</td>
                        <td class="{{ $history->change_amount > 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($history->change_amount, 2) }}
                        </td>
                        <td>{{ number_format($history->previous_budget, 2) }}</td>
                        <td>{{ number_format($history->current_budget, 2) }}</td>
                        <td>
                            <span class="badge {{ $history->action === 'add' ? 'bg-success' : 'bg-warning' }}">
                                {{ $history->action === 'add' ? 'Penambahan' : 'Perubahan' }}
                            </span>
                        </td>
                        <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Script DataTables --}}
    <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#budget-table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 10,
                language: {
                    paginate: {
                        previous: "« Sebelumnya",
                        next: "Berikutnya »"
                    },
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    zeroRecords: "Tidak ada data yang ditemukan",
                }
            });
        });
    </script>
</div></main></x-kasir.layouts>


