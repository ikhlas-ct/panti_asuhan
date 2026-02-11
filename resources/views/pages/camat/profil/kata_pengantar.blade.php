@extends('layouts.user.user')

@section('title', 'Settings Kata Pembuka')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Settings Kata Pembuka</div>
                    </div>
                    <div class="card-body">
                        @include('partials.alert.alert')
                        <form action="{{ route('camat.pengantar.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="gambar_pengantar" class="form-label">Gambar Pengantar</label>
                                        <input type="file" class="form-control" id="gambar_pengantar" name="gambar_pengantar" accept="image/*">
                                        @if($settings->gambar_pengantar)
                                            <img src="{{ asset( $settings->gambar_pengantar) }}" alt="Gambar Pengantar" class="img-fluid mt-2" style="height: 150px; object-fit: cover;">
                                        @endif
                                        @error('gambar_pengantar')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="title_pengantar" class="form-label">Title Pengantar</label>
                                        <input type="text" class="form-control @error('title_pengantar') is-invalid @enderror" id="title_pengantar" name="title_pengantar" value="{{ old('title_pengantar', $settings->title_pengantar ?? '') }}" placeholder="Masukkan Title Pengantar">
                                        @error('title_pengantar')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="paragraf_pengantar" class="form-label">Paragraf Pengantar</label>
                                        <textarea class="form-control summernote @error('paragraf_pengantar') is-invalid @enderror" id="paragraf_pengantar" name="paragraf_pengantar" rows="10">{{ old('paragraf_pengantar', $settings->paragraf_pengantar ?? '') }}</textarea>
                                        @error('paragraf_pengantar')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card-action text-end mt-4">
                                <button type="submit" class="btn btn-success">Simpan</button>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 500,
            placeholder: 'Masukkan paragraf pengantar di sini...',
            disableDragAndDrop: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
            ],
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Times New Roman', 'Roboto', 'Tahoma', 'Verdana'],
            fontNamesIgnoreCheck: ['Roboto']
        });
    });
</script>
@endsection
