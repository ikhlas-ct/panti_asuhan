@extends('layouts.user.user')

@section('title', 'Daftar Team')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Team</div>
            </div>
            <div class="card-body">
                <a href="{{ route('pegawai.create') }}" class="btn btn-primary mb-3">
                    Tambah Team
                </a>
                @include('partials.alert.alert')
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pegawais as $index => $pegawai)
                                <tr>
                                    <td>{{ $pegawais->firstItem() + $index }}</td>
                                    <td>
                                        @if ($pegawai->foto_profil)
                                            <img src="{{ asset('storage/' . $pegawai->foto_profil) }}" alt="{{ $pegawai->nama }}" style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            Tidak Ada Foto
                                        @endif
                                    </td>
                                    <td>{{ $pegawai->nama }}</td>
                                    <td>{{ $pegawai->posisi ?? 'Tidak Ditentukan' }}</td>
                                    <td>{{ $pegawai->email ?? '-' }}</td>
                                    <td>{{ $pegawai->nohp ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('pegawai.edit', $pegawai->id_pegawai) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('pegawai.destroy', $pegawai->id_pegawai) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus pegawai ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $pegawais->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
