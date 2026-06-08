<x-Supvis.SupvisLayouts>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Produk</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk.update', $produk->id) }}" onsubmit="removeFormatBeforeSubmit()" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="produk_nama" class="form-label">Nama Produk</label>
                <input type="text" class="form-control bg-light text-secondary border" id="produk_nama" name="produk_nama"
                    value="{{ old('produk_nama', $produk->produk_nama) }}" readonly>
            </div>

            <div class="mb-3">
                <label for="produk_harga" class="form-label">Harga Produk</label>
                <input type="text" class="form-control bg-light text-secondary border" id="produk_harga" name="produk_harga" required
                    value="{{ old('produk_harga', number_format($produk->produk_harga, 0, ',', '.')) }}"
                    onkeyup="formatRupiah(this)" readonly>
            </div>

            <div class="mb-3">
                <label for="produk_diskon" class="form-label">Diskon (Rp)</label>
                <input type="text" class="form-control bg-light text-secondary border" id="produk_diskon" name="produk_diskon"
                    value="{{ old('produk_diskon', number_format($produk->produk_diskon, 0, ',', '.')) }}"
                    onkeyup="formatRupiah(this)" readonly>
            </div>

            <div class="mb-3">
                <label for="produk_insentif" class="form-label">Insentif (Rp)</label>
                <input type="text" class="form-control bg-light text-secondary border" id="produk_insentif" name="produk_insentif"
                    value="{{ old('produk_insentif', number_format($produk->produk_insentif, 0, ',', '.')) }}"
                    onkeyup="formatRupiah(this)" readonly>
            </div>

            <div class="mb-3">
                <label for="produk_stok" class="form-label">Jumlah Stok</label>
                <input type="number" class="form-control" id="produk_stok" name="produk_stok"
                    value="{{ old('stok_option') == 'tambah' ? '' : old('produk_stok', $produk->produk_stok) }}"
                    placeholder="{{ old('stok_option') == 'tambah' ? 'Masukkan tambahan stok yang ada' : '' }}"
                    required>
                <small class="text-muted">Masukkan jumlah stok yang ingin ditambahkan atau mengganti stok lama.</small>
            </div>

            @if ($produk->produk_stok > 0)
                <div class="mb-3">
                    <label for="stok_option" class="form-label">Apa yang ingin Anda lakukan dengan stok lama?</label>
                    <select class="form-control" id="stok_option" name="stok_option" required>
                        <option value="ganti" {{ old('stok_option') == 'ganti' ? 'selected' : '' }}>Ganti Stok Lama
                        </option>
                        <option value="tambah" {{ old('stok_option') == 'tambah' ? 'selected' : '' }}>Tambah Stok Lama
                        </option>
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label for="produk_detail" class="form-label">Detail Produk</label>
                <textarea class="form-control bg-light text-secondary border" id="produk_detail" name="produk_detail" rows="4" readonly>{{ old('produk_detail', $produk->produk_detail) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="merchandises" class="form-label">Merchandise</label>
                <div>
                    <fieldset disabled="disabled">
                    @foreach ($merchandises as $merchandise)
                        <div class="form-check">
                            <input type="checkbox" name="merchandises[]" id="merchandise_{{ $merchandise->id }}"
                                value="{{ $merchandise->id }}" class="form-check-input"
                                {{ is_array(old('merchandises', $produk->merchandises->pluck('id')->toArray())) && in_array($merchandise->id, old('merchandises', $produk->merchandises->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <label class="form-check-label" for="merchandise_{{ $merchandise->id }}">
                                {{ $merchandise->merch_nama }}
                            </label>
                        </div>
                    @endforeach
                    </fieldset>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script>
        function formatRupiah(angka) {
            let numberString = angka.value.replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            angka.value = rupiah;
        }

        function removeFormatBeforeSubmit() {
            let hargaInput = document.getElementById('produk_harga');
            let diskonInput = document.getElementById('produk_diskon');
            let insentifInput = document.getElementById('produk_insentif');
            let hargaValue = hargaInput.value.replace(/\./g, '').replace(',', '.');
            let diskonValue = diskonInput.value.replace(/\./g, '').replace(',', '.');
            let insentifValue = insentifInput.value.replace(/\./g, '').replace(',', '.');

            hargaInput.value = hargaValue;
            diskonInput.value = diskonValue === '' ? 0 : parseInt(diskonValue);
            insentifInput.value = insentifValue === '' ? 0 : parseInt(insentifValue);
        }
    </script>
</x-Supvis.SupvisLayouts>

