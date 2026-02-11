@extends('layouts.user.user')

@section('title', 'Manage Hero beranda')

@section('content')

<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Hero beranda</div>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahHeroslideModal">
                    Tambah Hero Beranda
                </button>
                @include('partials.alert.alert')
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slides  as $index => $slide)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ asset( $slide->image) }}" alt="{{ $slide->title }}" style="width: 150px;">
                                </td>
                                <td>{{ $slide->title }}</td>
                                <td>{{ Str::limit($slide->description, 100) }}</td>
                                <td>
                                    <a href="{{ route('camat.settings.heroslide.edit', $slide->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('camat.settings.heroslide.destroy', $slide->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus slide ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Tambah Hero Slide -->
<div class="modal fade" id="tambahHeroslideModal" tabindex="-1" aria-labelledby="tambahHeroslideLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahHeroslideLabel">Tambah Hero Slide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('camat.settings.heroslide.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Judul Slide -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Slide</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                    </div>
                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>
                    <!-- Gambar -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        <input type="file" name="image" id="image" class="form-control" required>
                        <small class="form-text text-muted">Format gambar: JPG, JPEG, PNG. Maksimal ukuran: 2MB.</small>
                        <small class="form-text text-muted">Ukuran gambar yang disarankan: 1920x1080px.</small>
                    </div>
                    <!-- Teks Tombol (Opsional) -->
                    <div class="mb-3">
                        <label for="button_text" class="form-label">Teks Tombol (Opsional)</label>
                        <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text') }}">
                    </div>
                    <!-- Link Tombol (Opsional) -->
                    <div class="mb-3">
                        <label for="button_link" class="form-label">Link Tombol (Opsional)</label>
                        <input type="url" name="button_link" id="button_link" class="form-control" value="{{ old('button_link') }}">
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan Hero Slide</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        @if($errors->any())
            $('#tambahHeroslideModal').modal('show');
        @endif
    });
</script>
@endsection
