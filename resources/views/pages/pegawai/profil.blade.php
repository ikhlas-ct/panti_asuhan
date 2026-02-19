@extends('layouts.user.user')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <!-- Form Update Profil -->
            <div class="card shadow-lg border-0 rounded-lg mb-5">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Profil Saya</h3>
                </div>
                <div class="card-body">
                    @include('partials.alert.alert')

                    <form action="{{ route('pegawai.profil_update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            <img
                                src="{{ optional($profil)->foto_profil ? asset(optional($profil)->foto_profil) : asset('default-image/default-user.png') }}"
                                alt="Foto Profil"
                                class="rounded-circle img-thumbnail"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        </div>

                        <div class="mb-3">
                            <label for="foto_profil" class="form-label fw-bold">Ganti Foto Profil</label>
                            <input type="file" name="foto_profil" id="foto_profil" class="form-control @error('foto_profil') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Format: jpeg, png, jpg. Maks. 2MB.</small>
                            @error('foto_profil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <!-- Nama Pegawai -->
                            <div class="col-12">
                                <label for="nama" class="form-label fw-bold">Nama Pegawai <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                       value="{{ old('nama', optional($profil)->nama ?? '') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Posisi -->
                            <div class="col-md-6">
                                <label for="posisi" class="form-label fw-bold">Posisi</label>
                                <input type="text" name="posisi" id="posisi" class="form-control @error('posisi') is-invalid @enderror"
                                       value="{{ old('posisi', optional($profil)->posisi ?? '') }}">
                                @error('posisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-6">
                                <label for="alamat" class="form-label fw-bold">Alamat</label>
                                <input type="text" name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                       value="{{ old('alamat', optional($profil)->alamat ?? '') }}">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- No HP -->
                            <div class="col-md-6">
                                <label for="nohp" class="form-label fw-bold">Nomor HP</label>
                                <input type="text" name="nohp" id="nohp" class="form-control @error('nohp') is-invalid @enderror"
                                       value="{{ old('nohp', optional($profil)->nohp ?? '') }}" maxlength="15">
                                @error('nohp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', optional($profil)->email ?? '') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', optional($profil)->deskripsi ?? '') }}</textarea>
                                <small class="text-muted">Deskripsi ini akan ditampilkan di konten Anda.</small>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sosial Media -->
                            <div class="col-md-4">
                                <label for="instagram" class="form-label fw-bold">Instagram</label>
                                <input type="text" name="instagram" id="instagram" class="form-control @error('instagram') is-invalid @enderror"
                                       value="{{ old('instagram', optional($profil)->instagram ?? '') }}">
                                @error('instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="twitter" class="form-label fw-bold">Twitter</label>
                                <input type="text" name="twitter" id="twitter" class="form-control @error('twitter') is-invalid @enderror"
                                       value="{{ old('twitter', optional($profil)->twitter ?? '') }}">
                                @error('twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="facebook" class="form-label fw-bold">Facebook</label>
                                <input type="text" name="facebook" id="facebook" class="form-control @error('facebook') is-invalid @enderror"
                                       value="{{ old('facebook', optional($profil)->facebook ?? '') }}">
                                @error('facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Simpan Perubahan Profil</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form Ubah Password -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Ubah Password</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('pegawai.password_update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="current_password" class="form-label fw-bold">Password Lama <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-warning btn-lg px-5">Simpan Password Baru</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection