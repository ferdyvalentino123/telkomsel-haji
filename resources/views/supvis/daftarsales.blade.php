<x-Supvis.SupvisLayouts>
    <div class="container">
        <h2 class="mb-4">Kelola Bertugas - Role Sales</h2>

        <div class="mb-3">
            <label>Bertugas</label>
            <select id="filter-bertugas" class="form-control">
                <option value="">Semua</option>
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Tempat Tugas</label>
            <input type="text" id="filter-tempat" class="form-control" placeholder="Filter Tempat Tugas">
        </div>
        
        <form action="{{ route('kasir.role-users.mass-update') }}" method="POST" id="massUpdateForm">
            @csrf
            

            <table class="table table-bordered table-striped" id="userTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Bertugas</th>
                        <th>Tempat Tugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @foreach ($users as $user)
                        <tr data-user-id="{{ $user->id }}">
                            <td>
                                <label for="user_{{ $user->id }}" class="form-check-label">{{ $user->role }}</label>
                                <input type="hidden" name="users[{{ $user->id }}][id]" value="{{ $user->id }}">
                            </td>
                            <td><input type="text" name="users[{{ $user->id }}][name]" value="{{ $user->name }}" class="form-control"></td>
                            <td><input type="text" name="users[{{ $user->id }}][email]" value="{{ $user->email }}" class="form-control"></td>
                            <td><input type="text" name="users[{{ $user->id }}][phone]" value="{{ $user->phone }}" class="form-control"></td>
                            <td><input type="text" name="users[{{ $user->id }}][bertugas]" value="{{ $user->bertugas }}" class="form-control"></td>
                            <td><input type="text" name="users[{{ $user->id }}][tempat_tugas]" value="{{ $user->tempat_tugas }}" class="form-control"></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm delete-row">Delete</button>
                                <input type="hidden" name="deleted_ids[]" value="{{ $user->id }}" disabled>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
            
            <button type="submit" class="btn btn-success">Update Bertugas Massal</button>
            <a href="{{ route('kasir.add_sales') }}">
                <button type="button" class="btn btn-secondary">Tambah</button>
            </a>
        </form>
        
    </div>

    <script>
        document.getElementById('select-all').onclick = function() {
            document.querySelectorAll('input[name="user_ids[]"]').forEach(cb => cb.checked = this.checked);
        }

        // Sorting based on tempat tugas
        window.addEventListener('DOMContentLoaded', () => {
            sortTable();
        });

        function sortTable() {
            const table = document.getElementById("userTableBody");
            const rows = Array.from(table.querySelectorAll("tr"));
            rows.sort((a, b) => {
                const at = a.children[5].textContent.trim().toLowerCase();
                const bt = b.children[5].textContent.trim().toLowerCase();
                return at.localeCompare(bt);
            });
            rows.forEach(row => table.appendChild(row));
        }

        // Filter
        document.getElementById('filter-bertugas').addEventListener('change', filterTable);
        document.getElementById('filter-tempat').addEventListener('input', filterTable);

        function filterTable() {
            const bertugasFilter = document.getElementById('filter-bertugas').value;
            const tempatFilter = document.getElementById('filter-tempat').value.toLowerCase();

            document.querySelectorAll('#userTableBody tr').forEach(row => {
                const bertugas = row.children[4].textContent.trim();
                const tempat = row.children[5].textContent.trim().toLowerCase();
                let show = true;

                if (bertugasFilter !== '' && bertugas !== (bertugasFilter === '1' ? 'Ya' : 'Tidak')) {
                    show = false;
                }
                if (tempatFilter && !tempat.includes(tempatFilter)) {
                    show = false;
                }
                row.style.display = show ? '' : 'none';
            });
        }

        // Add Row
        let userIndex = -1; // For new users
        document.getElementById('add-row').addEventListener('click', function () {
            const tbody = document.querySelector('table tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="checkbox">
                    <input type="hidden" name="users[${userIndex}][id]" value="">
                </td>
                <td><input type="text" name="users[${userIndex}][name]" class="form-control"></td>
                <td><input type="text" name="users[${userIndex}][email]" class="form-control"></td>
                <td><input type="text" name="users[${userIndex}][phone]" class="form-control"></td>
                <td><input type="text" name="users[${userIndex}][bertugas]" class="form-control"></td>
                <td><input type="text" name="users[${userIndex}][tempat_tugas]" class="form-control"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-row">Delete</button>
                    <input type="hidden" name="deleted_ids[]" disabled>
                </td>
            `;
            tbody.appendChild(newRow);
            userIndex--; // Use negative index to avoid collision with DB IDs
        });

        // Delete Row
        document.querySelectorAll('.delete-row').forEach(button => {
            button.addEventListener('click', function () {
                const row = this.closest('tr');
                row.querySelector('input[name^="deleted_ids"]').disabled = false;
                row.style.display = 'none'; // Hide visually
            });
        });
    </script>
</x-Supvis.SupvisLayouts>

