@extends('layouts.user.user')

@section('title', 'Gallery')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">Daftar Gallery</div>
                <a href="{{ route('gallery.create') }}" class="btn btn-primary">Tambah Gallery</a>
            </div>
            <div class="card-body">
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
                            @forelse ($galleries as $index => $item)
                                <tr>
                                    <td>{{ $galleries->firstItem() + $index }}</td>
                                    <td class="text-center">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="width:150px; height:auto;">
                                        @endif
                                    </td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->description, 120) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('gallery.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('gallery.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data gallery.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $galleries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
