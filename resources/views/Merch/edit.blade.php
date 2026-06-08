<x-Supvis.SupvisLayouts>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Merchandise</h2>
        <form action="{{ route('merch.update', $merchandise->id) }}" method="POST" class="shadow p-4 rounded" style="background-color: #f8f9fa;">
            @csrf
            @method('PUT')

            <x-form-group 
                label="Nama Merchandise" 
                name="merch_nama" 
                type="text" 
                :required="false" 
                :value="$merchandise->merch_nama"
                :readonly="true"
            />
            
            {{-- kalo pake components (x-form-group) & (x-form-button) langsung kacaw --}}
            <div class="mb-3">
                <label for="merch_detail" class="form-label" style="font-weight: bold;">Detail Merchandise</label>
                <textarea name="merch_detail" id="merch_detail" class="form-control bg-light text-secondary border" rows="4" placeholder="Masukkan detail merchandise" readonly>{{ $merchandise->merch_detail }}</textarea>
            </div>

            <div class="mb-3">
                <label for="merch_stok" class="form-label" style="font-weight: bold;">Stok Merchandise</label>
                <input type="number" name="merch_stok" id="merch_stok" class="form-control" value="{{ $merchandise->merch_stok }}" placeholder="Masukkan stok merchandise" required>
            </div>

            <div class="mb-3">
                <label for="stok_option" class="form-label" style="font-weight: bold;">Apa yang ingin Anda lakukan dengan stok lama?</label>
                <select class="form-control" id="stok_option" name="stok_option" required>
                    <option value="ganti">Ganti Stok Lama</option>
                    <option value="tambah">Tambah Stok Lama</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn text-white" style="flex: 1; margin-right: 10px; background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC); border: none;">
                    Perbarui Merch
                </button>
                <a href="{{ route('merch.index') }}" class="btn btn-secondary" style="flex: 1; margin-left: 10px;">Batal</a>
            </div>
            {{--  --}}

        </form>
    </div>
</x-Supvis.SupvisLayouts>

