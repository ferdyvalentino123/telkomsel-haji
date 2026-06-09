
<x-kasir.layouts><main class="content"><div class="container-fluid p-0">
    <div class="container mt-4">
        <h2 class="text-center mb-4">Budget Insentif</h2>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card p-4 shadow-sm mb-4">
            <div class="text-start">
                <h5 style="font-size: 1.20rem;">Total Budget:</h5>
                <h6 class="text-primary" style="font-size: 1.0rem;">{{ number_format($totalBudget, 2) }}</h6>
                <hr>
                <h5 style="font-size: 1.20rem;">Total Insentif:</h5> 
                <h6 class="text-danger" style="font-size: 1.0rem;">{{ number_format($totalInsentif, 2) }}</h6> 
                <hr>
                <h5 style="font-size: 1.20rem;">Sisa Budget:</h5> 
                <h6 class="text-success" style="font-size: 1.0rem;">{{ number_format($sisaBudget, 2) }}</h6>
            </div>
        </div>

        <div class="card p-4 shadow-sm">
            <h3 class="mb-4">Update Budget Insentif</h3>
            <form action="{{ route('kasir.budget_insentif.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Aksi:</label><br>
                    <input type="radio" name="action" value="tambah" id="tambah" checked>
                    <label for="tambah">Tambah</label>
                    <input type="radio" name="action" value="ganti" id="ganti" class="ms-3">
                    <label for="ganti">Ganti</label>
                </div>

                <div class="mb-3">
                    <label for="total_insentif" class="form-label">Jumlah Budget:</label>
                    <input type="number" class="form-control" name="total_insentif" id="total_insentif"
                        value="{{ old('total_insentif') }}" required>
                </div>

                <button type="submit" class="btn btn-custom btn-lg">Simpan</button>
            </form>
        </div>
    </div>

    <style>
        .btn-custom {
            background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC);
            color: white; 
            border: none; 
            border-radius: 5px;
            padding: 10px 20px;
        }

        .btn-custom:hover {
            opacity: 0.9;
        }
    </style>
</div></main></x-kasir.layouts>


