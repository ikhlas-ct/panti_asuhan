@extends('layouts.user.user')

@section('title', 'Settings Website')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Pengaturan Website</div>
                    </div>
                    @include('partials.alert.alert')
                    <div class="card-body">
                        <form action="{{ route('camat.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">
                                    <div class="mb-3 row align-items-center">
                                        <div class="col-md-6">
                                            <label for="logo" class="form-label fw-bold">Logo Website</label>
                                            <p class="text-danger small">* Rekomendasi ukuran 2000 x 1300 pixel</p>
                                            <input type="file" class="form-control border-secondary mt-2" id="logo" name="logo" accept="image/*">
                                            @error('logo')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 text-end">
                                            @if($settings->logo)
                                                <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="img-fluid rounded-2 border border-secondary" style="object-fit: cover; height: 150px;">
                                            @else
                                                <img src="{{ asset('user/img/default-logo.png') }}" alt="Default Logo" class="img-fluid rounded-2 border border-secondary" style="object-fit: cover; height: 150px;">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Website</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $settings->nama) }}" placeholder="Masukkan nama Website">
                                        @error('nama')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nomor_telepon" class="form-label">Nomor Telepon Website</label>
                                        <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $settings->nomor_telepon) }}" placeholder="Contoh: 0751-123456">
                                        @error('nomor_telepon')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                        <div class="mb-3">
                                        <label for="slogan" class="form-label">Slogan</label>
                                        <input type="text" class="form-control @error('slogan') is-invalid @enderror" id="slogan" name="slogan" value="{{ old('slogan', $settings->slogan) }}" placeholder="Masukkan slogan">
                                        @error('slogan')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat Website</label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', $settings->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Website</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $settings->email) }}" placeholder="Contoh: info@kecamatanivkoto.go.id">
                                        @error('email')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social_facebook" class="form-label">Facebook</label>
                                                <input type="url" class="form-control @error('social_facebook') is-invalid @enderror" id="social_facebook" name="social_facebook" value="{{ old('social_facebook', $settings->social_facebook) }}" placeholder="https://facebook.com/...">
                                                @error('social_facebook')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social_instagram" class="form-label">Instagram</label>
                                                <input type="url" class="form-control @error('social_instagram') is-invalid @enderror" id="social_instagram" name="social_instagram" value="{{ old('social_instagram', $settings->social_instagram) }}" placeholder="https://instagram.com/...">
                                                @error('social_instagram')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social_twitter" class="form-label">Twitter / X</label>
                                                <input type="url" class="form-control @error('social_twitter') is-invalid @enderror" id="social_twitter" name="social_twitter" value="{{ old('social_twitter', $settings->social_twitter) }}" placeholder="https://twitter.com/...">
                                                @error('social_twitter')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social_youtube" class="form-label">YouTube</label>
                                                <input type="url" class="form-control @error('social_youtube') is-invalid @enderror" id="social_youtube" name="social_youtube" value="{{ old('social_youtube', $settings->social_youtube) }}" placeholder="https://youtube.com/...">
                                                @error('social_youtube')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-action text-end mt-4">
                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                <a href="{{ url()->previous() }}" class="btn btn-danger">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection