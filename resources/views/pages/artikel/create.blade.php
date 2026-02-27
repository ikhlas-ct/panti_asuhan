@extends('layouts.user.user')

@section('title', 'Tambah ' . ucfirst($jenis))

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tambah {{ ucfirst($jenis) }}</div>
                </div>
                <div class="card-body">
                    @include('partials.alert.alert')

                    <form action="{{ route('konten.store', $jenis) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Judul -->
                        <div class="row">
                            <div class="col-md-12">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="judul" name="judul"
                                    value="{{ old('judul') }}" required>
                                @error('judul')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Kategori (untuk curated-journey dan berita) -->
                        @if (in_array($jenis, ['curated-journey', 'berita']))
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="input-group mb-3">
                                        <select class="form-select" name="kategori" required>
                                            <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih
                                                Kategori</option>
                                            @foreach ($kategori as $item)
                                                <option value="{{ $item->id_kategori }}"
                                                    {{ old('kategori') == $item->id_kategori ? 'selected' : '' }}>
                                                    {{ $item->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('kategori')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Duration, Price, Badge (hanya jika ethical) -->
                        @if ($jenis == 'ethical')
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label for="duration" class="form-label">Duration</label>
                                    <input type="text" class="form-control" id="duration" name="duration"
                                        value="{{ old('duration') }}">
                                    @error('duration')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price"
                                        value="{{ old('price') }}">
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="badge" class="form-label">Badge</label>
                                    <input type="text" class="form-control" id="badge" name="badge"
                                        value="{{ old('badge') }}">
                                    @error('badge')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Gambar -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="gambar" class="form-label">Gambar <small>(wajib)</small></label>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*"
                                    required>
                                @error('gambar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="ringkasan" class="form-label">Ringkasan</label>
                                <textarea class="form-control" id="ringkasan" name="ringkasan" rows="3" maxlength="255"
                                    placeholder="Tulis ringkasan singkat yang akan tampil di halaman utama...">{{ old('ringkasan') }}</textarea>

                                @error('ringkasan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Isi -->
                        <div class="summernote-wrapper mb-3 mt-3">
                            <label for="isi" class="form-label">Isi {{ ucfirst($jenis) }}</label>
                            <textarea class="form-control summernote" id="isi" name="isi" rows="5" required>{{ old('isi') }}</textarea>
                            @error('isi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('konten.index', $jenis) }}" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    @include('pages.artikel.blog_summernote')
@endsection

@endsection
