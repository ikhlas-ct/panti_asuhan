{{-- filepath: c:\storage2\resources\views\pages\camat\heroslide\edit.blade.php --}}
@extends('layouts.user.user')

@section('title', 'Edit Hero Slide')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Hero Slide</div>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Edit Hero Slide -->
                <form action="{{ route('camat.settings.heroslide.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Judul -->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="title" class="form-label">Judul </label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $slide->title) }}" >
                            <small class="form-text text-muted">jika tidak ingin mengisi silakan kosongkan.</small>

                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="description" class="form-label">Deskripsi </label>
                            <textarea class="form-control" id="description" name="description" rows="4" >{{ old('description', $slide->description) }}</textarea>
                            <small class="form-text text-muted">jika tidak ingin mengisi silakan kosongkan.</small>

                        </div>
                    </div>

                    <!-- Gambar -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="image" class="form-label">Gambar <span class="text-danger">*</span></label>
                            @if(isset($slide->image))
                                <div class="mb-2">
                                    <img src="{{ asset($slide->image) }}" alt="{{ $slide->title }}" class="img-fluid" style="max-height: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" {{ $slide->image ? '' : 'required' }}>
                            <small class="form-text text-muted">Format gambar: JPG, JPEG, PNG. Maksimal ukuran: 2MB.</small>
                            <small class="form-text text-muted">Jika tidak ingin mengganti gambar, silakan kosongkan.</small>
                        </div>
                    </div>

                    <!-- Teks Tombol -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="button_text" class="form-label">Teks Tombol <small class="text-muted">(Opsional)</small></label>
                            <input type="text" class="form-control" id="button_text" name="button_text" value="{{ old('button_text', $slide->button_text) }}">
                        </div>
                    </div>

                    <!-- Link Tombol -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="button_link" class="form-label">Link Tombol <small class="text-muted">(Opsional)</small></label>
                            <input type="url" class="form-control" id="button_link" name="button_link" value="{{ old('button_link', $slide->button_link) }}">
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success">Perbarui Slide</button>
                        <a href="{{ route('camat.settings.heroslide') }}" class="btn btn-danger">Batal</a>
                    </div>
                </form>
                <!-- End Form Edit Hero Slide -->
            </div>
        </div>
    </div>
</div>
@endsection
