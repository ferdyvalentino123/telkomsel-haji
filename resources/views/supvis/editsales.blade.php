<x-supvis.layouts>
    <div class="container">
        <h2 class="mb-4">Edit Tugas Role User</h2>

        <form action="{{ route('kasir.role-users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Bertugas</label>
                <select name="bertugas" class="form-control">
                    <option value="1" {{ $user->bertugas ? 'selected' : '' }}>Ya</option>
                    <option value="0" {{ !$user->bertugas ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Tempat Tugas</label>
                <input type="text" name="tempat_tugas" class="form-control" value="{{ $user->tempat_tugas }}">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</x-supvis.layouts>

