<x-Kasir.KasirLayouts><main class="content"><div class="container-fluid p-0">
    <style>
        .profile-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }

        .avatar-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid #2575FC;
            overflow: hidden;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-preview i {
            font-size: 80px;
            color: #ccc;
        }

        .photo-upload-label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #2575FC;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-upload-label:hover {
            background: #1e66d9;
        }

        .photo-upload-label i {
            color: white;
            font-size: 18px;
        }

        #photo {
            display: none;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-save {
            flex: 1;
            background: #23a0b0;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-save:hover {
            background: #1c828f;
        }

        .btn-cancel {
            flex: 1;
            background: #ff4d4d;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-cancel:hover {
            background: #d43f3f;
        }

        .error {
            color: #ff4d4d;
            font-size: 14px;
            margin-top: 6px;
            margin-bottom: 10px;
        }
    </style>

    <div class="container py-5">
        <h1 class="text-center mb-5 fw-bold text-dark">Tambah Sales</h1>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-4">
                    <form id="addSalesForm" action="{{ route('kasir.store_sales') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="profile-section">
                            <div class="avatar-container">
                                <div class="avatar-preview">
                                    <i class="fas fa-user"></i>
                                </div>
                                <label for="photo" class="photo-upload-label shadow">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(this)">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Sales</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama sales" required maxlength="20">
                            <div class="error" id="nameError"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required>
                            <div class="error" id="emailError"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">PIN</label>
                            <input type="text" name="pin" id="pin" class="form-control" placeholder="Masukkan PIN (4-6 digit)" required maxlength="6" minlength="4">
                            <div class="error" id="pinError"></div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="">Pilih Role</option>
                                <option value="sales">Sales</option>
                            </select>
                            <div class="error" id="roleError"></div>
                        </div>

                        <div class="btn-container">
                            <button type="submit" class="btn btn-save shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Sales
                            </button>
                            <button type="button" class="btn btn-cancel shadow-sm" onclick="confirmCancel()">
                                <i class="fas fa-times-circle me-2"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewImage(input) {
            const preview = document.querySelector('.avatar-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '<i class="fas fa-user"></i>';
            }
        }

        function confirmCancel() {
            Swal.fire({
                title: "Batalkan?",
                text: "Form akan dikosongkan.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Batal"
            }).then(r => {
                if (r.isConfirmed) {
                    document.getElementById("addSalesForm").reset();
                    document.querySelector('.avatar-preview').innerHTML = '<i class="fas fa-user"></i>';
                }
            });
        }

        $('#addSalesForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: "Simpan data?",
                text: "Sales baru akan ditambahkan.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan"
            }).then(r => {
                if (r.isConfirmed) form.submit();
            });
        });
    </script>
</div></main></x-Kasir.KasirLayouts>


