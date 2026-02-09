@extends('layouts.user.user')

@section('title', 'Edit Gallery')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Gallery</div>
            </div>
            <div class="card-body">
                @include('partials.alert.alert')

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        @if($gallery->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="img-fluid" style="max-height:200px;">
                            </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $gallery->title) }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $gallery->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="button_text" class="form-label">Teks Tombol (opsional)</label>
                        <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text', $gallery->button_text) }}">
                    </div>

                    <div class="mb-3">
                        <label for="button_url" class="form-label">Link Tombol (opsional)</label>
                        <input type="url" name="button_url" id="button_url" class="form-control" value="{{ old('button_url', $gallery->button_url) }}">
                    </div>

                    <div class="text-end">
                        <a href="{{ route('gallery.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
