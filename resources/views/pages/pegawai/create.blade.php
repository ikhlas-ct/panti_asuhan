@extends('layouts.user.user')

@section('title', 'Tambah Pegawai')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Tambah Pegawai</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary py-3 text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-plus me-2"></i>
                            <h5 class="mb-0">Form Tambah Pegawai</h5>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @include('partials.alert.alert')

                        <form action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            @csrf

                            <!-- Data Pegawai -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header mb-4">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-wrapper bg-primary rounded-circle me-3 p-2">
                                                <i class="fas fa-id-card text-white"></i>
                                            </div>
                                            <h4 class="mb-0">Data Pegawai</h4>
                                        </div>
                                        <hr class="mt-2">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label fw-semibold">Nama <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" id="nama" name="nama"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="posisi" class="form-label fw-semibold">Posisi</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                        <input type="text" id="posisi" name="posisi"
                                            class="form-control @error('posisi') is-invalid @enderror"
                                            value="{{ old('posisi') }}" placeholder="Masukkan posisi/jabatan">
                                        @error('posisi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" id="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="contoh@email.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nohp" class="form-label fw-semibold">No HP</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" id="nohp" name="nohp"
                                            class="form-control @error('nohp') is-invalid @enderror"
                                            value="{{ old('nohp') }}" placeholder="" maxlength="20">
                                        @error('nohp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-semibold">Alamat</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3"
                                        placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="4" placeholder="Masukkan deskripsi tentang pegawai">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Media Sosial -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="fw-semibold text-secondary mb-3">
                                        <i class="fas fa-share-alt me-2"></i>Media Sosial
                                    </h5>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="instagram" class="form-label">
                                        <i class="fab fa-instagram text-danger me-1"></i>Instagram
                                    </label>
                                    <input type="text" id="instagram" name="instagram"
                                        class="form-control @error('instagram') is-invalid @enderror"
                                        value="{{ old('instagram') }}" placeholder="@username">
                                    @error('instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="twitter" class="form-label">
                                        <i class="fab fa-twitter text-info me-1"></i>Twitter
                                    </label>
                                    <input type="text" id="twitter" name="twitter"
                                        class="form-control @error('twitter') is-invalid @enderror"
                                        value="{{ old('twitter') }}" placeholder="@username">
                                    @error('twitter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="facebook" class="form-label">
                                        <i class="fab fa-facebook text-primary me-1"></i>Facebook
                                    </label>
                                    <input type="text" id="facebook" name="facebook"
                                        class="form-control @error('facebook') is-invalid @enderror"
                                        value="{{ old('facebook') }}" placeholder="nama.profile">
                                    @error('facebook')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Foto Profil -->
                            <div class="mb-4">
                                <label for="foto_profil" class="form-label fw-semibold">Foto Profil</label>
                                <div class="card border-dashed p-3">
                                    <div class="mb-3">
                                        <input type="file" id="foto_profil" name="foto_profil"
                                            class="form-control @error('foto_profil') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Format: JPEG, PNG, JPG. Maks. 2MB.
                                        </div>
                                        @error('foto_profil')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="image-preview d-none">
                                                <p class="text-muted mb-2">Pratinjau:</p>
                                                <img id="imagePreview" class="img-thumbnail rounded-circle"
                                                    style="width: 120px; height: 120px; object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data User -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header mb-4">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-wrapper bg-success rounded-circle me-3 p-2">
                                                <i class="fas fa-user-circle text-white"></i>
                                            </div>
                                            <h4 class="mb-0">Data User (Kosongkan Jika tidak ingin menambah user login)
                                            </h4>
                                            <span class="badge bg-secondary ms-3">Opsional</span>
                                        </div>
                                        <hr class="mt-2">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label fw-semibold">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        <input type="text" id="username" name="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username') }}" placeholder="Masukkan username">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-semibold">Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                        <select id="status" name="status"
                                            class="form-control @error('status') is-invalid @enderror">
                                            <option value="">-- Pilih Status --</option>
                                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>
                                                Aktif
                                            </option>
                                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>
                                                Nonaktif
                                            </option>
                                        </select>

                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Masukkan password baru">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Kosongkan jika tidak ingin menambahkan password.
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi
                                        Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Konfirmasi password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between border-top pt-4">
                                        <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary px-4">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </a>
                                        <div>
                                            <button type="reset" class="btn btn-secondary me-2 px-4">
                                                <i class="fas fa-redo me-2"></i>Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="fas fa-save me-2"></i>Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: 0;
        }

        .border-dashed {
            border-style: dashed !important;
            border-color: #dee2e6;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-header h4 {
            color: #2c3e50;
        }

        .form-label {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .form-control:focus+.input-group-text,
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .current-photo img {
            border: 3px solid #dee2e6;
        }

        .image-preview img {
            border: 3px solid #86b7fe;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .d-flex.justify-content-between>div {
                width: 100%;
                display: flex;
                justify-content: space-between;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Preview image upload
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const previewContainer = document.querySelector('.image-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('d-none');
            }
        }

        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                button.classList.remove('fa-eye');
                button.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                button.classList.remove('fa-eye-slash');
                button.classList.add('fa-eye');
            }
        }

        // Form validation
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
@endpush
