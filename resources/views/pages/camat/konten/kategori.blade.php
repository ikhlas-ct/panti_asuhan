@extends('layouts.user.user')

@section('title', 'Kategori Konten')

@section('content')
    <div class="container">
        <div class="page-inner">

            <!-- TABLE KATEGORI -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Kategori</div>
                </div>

                <div class="card-body">

                    <!-- Search + Tombol Tambah -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                        <!-- Form Search -->
                        <form method="GET" class="flex-grow-1">
                            <div class="input-group" style="max-width: 420px;">
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Cari nama kategori atau slug..."
                                       value="{{ $search ?? '' }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>

                        <!-- Tombol Tambah -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </button>
                    </div>

                    @include('partials.alert.alert')

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Kategori</th>
                                    <th>Slug</th>
                                    <th>Icon</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $index => $kat)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($kategoris->currentPage() - 1) * $kategoris->perPage() + $index + 1 }}
                                        </td>
                                        <td>{{ $kat->nama_kategori }}</td>
                                        <td><code>{{ $kat->slug }}</code></td>
                                        <td class="text-center"><i class="{{ $kat->icon }}"></i></td>

                                        <td class="text-center">
                                            <span class="badge {{ $kat->status ? 'bg-success' : 'bg-danger' }}">
                                                {{ $kat->status ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal-{{ $kat->id_kategori }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal-{{ $kat->id_kategori }}" tabindex="-1"
                                        aria-labelledby="editModalLabel-{{ $kat->id_kategori }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('kategori.update', $kat->id_kategori) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel-{{ $kat->id_kategori }}">Edit Kategori</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Kategori</label>
                                                            <input type="text" name="nama_kategori" class="form-control"
                                                                value="{{ old('nama_kategori', $kat->nama_kategori) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Icon</label>
                                                            <input type="text" name="icon" class="form-control"
                                                                value="{{ old('icon', $kat->icon) }}"
                                                                placeholder="fa-solid fa-utensils">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select">
                                                                <option value="1" {{ $kat->status ? 'selected' : '' }}>Aktif</option>
                                                                <option value="0" {{ !$kat->status ? 'selected' : '' }}>Nonaktif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $kategoris->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>

            <!-- Modal Create -->
            <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('kategori.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="createModalLabel">Tambah Kategori</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Kategori</label>
                                    <input type="text" name="nama_kategori" class="form-control"
                                        value="{{ old('nama_kategori') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon</label>
                                    <input type="text" name="icon" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
