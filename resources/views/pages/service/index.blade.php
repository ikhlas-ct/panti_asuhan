{{-- resources/views/pages/service/index.blade.php --}}
@extends('layouts.user.user')

@section('title', 'Daftar ' . ucfirst($type))

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">Daftar {{ ucfirst($type) }}</div>
                <a href="{{ route('service.create', $type) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah {{ ucfirst($type) }}
                </a>
            </div>

            <div class="card-body">
                @include('partials.alert.alert')

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('service.index', $type) }}">
                    <div class="input-group mb-3">
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                               class="form-control"
                               placeholder="Cari {{ $type === 'transportasi' ? 'judul, deskripsi, atau harga' : 'judul atau deskripsi' }}...">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th width="5%">No</th>
                                @if(in_array($type, ['tema', 'layanan', 'etika', 'keunggulan', 'informasi']))
                                    <th width="10%">Icon</th>
                                @endif
                                <th>Judul</th>
                                @if(in_array($type, ['tema', 'layanan', 'etika', 'keunggulan', 'transportasi']))
                                    <th>Deskripsi</th>
                                @endif
                                @if($type === 'transportasi')
                                    <th>Price</th>
                                    <th>Gambar</th>
                                @endif
                                <th width="8%">Order</th>
                                @if(in_array($type, ['tema', 'layanan', 'transportasi']))
                                    <th width="10%">Steps</th>
                                @endif
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($services as $index => $service)
                                <tr>
                                    <td class="text-center">{{ $services->firstItem() + $index }}</td>
                                    @if(in_array($type, ['tema', 'layanan', 'etika', 'keunggulan', 'informasi']))
                                        <td class="text-center">
                                            @if($service->kategori && $service->kategori->icon)
                                                <i class="{{ $service->kategori->icon }} fa-2x"></i>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif
                                    <td><strong>{{ $service->title ?? '-' }}</strong></td>
                                    @if(in_array($type, ['tema', 'layanan', 'etika', 'keunggulan', 'transportasi']))
                                        <td>{{ Str::limit($service->description ?? '-', 100) }}</td>
                                    @endif
                                    @if($type === 'transportasi')
                                        <td>{{ $service->price ?? '-' }}</td>
                                        <td>
                                            @if ($service->gambar)
                                                <img src="{{ asset('storage/' . $service->gambar) }}"
                                                     alt="{{ $service->title }}" width="100">
                                            @else
                                                Tidak Ada Gambar
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-center font-weight-bold">{{ $service->order ?? '-' }}</td>
                                    @if(in_array($type, ['tema', 'layanan', 'transportasi']))
                                        <td class="text-center">
                                            <span class="badge badge-primary">{{ $service->steps_count ?? 0 }} step</span>
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <a href="{{ route('service.edit', [$type, $service->id]) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('service.destroy', [$type, $service->id]) }}"
                                              method="POST" style="display:inline-block;"
                                              onsubmit="return confirm('Yakin ingin menghapus {{ addslashes($service->title ?? '-') }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ in_array($type, ['tema', 'layanan']) ? 7 :
                                                  ($type === 'transportasi' ? 8 :
                                                  ($type === 'informasi' ? 5 : 6)) }}"
                                        class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                        <p class="mb-0">Belum ada data {{ $type }}.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
