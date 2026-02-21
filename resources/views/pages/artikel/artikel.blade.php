{{-- resources/views/pages/konten/index.blade.php --}}
@extends('layouts.user.user')

@section('title', 'Daftar ' . ucfirst($jenis))

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar {{ ucfirst($jenis) }}</div>
                </div>
                <div class="card-body">
                    <a href="{{ route('konten.create', $jenis) }}" class="btn btn-primary mb-3">
                        Tambah {{ ucfirst($jenis) }}
                    </a>

                    <form method="GET" action="{{ route('konten.index', $jenis) }}">
                        <div class="input-group mb-3">
                            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                                placeholder="Cari Penulis, judul, dan ringkasan...">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </form>

                    @include('partials.alert.alert')

                    <div class="table-responsive">
                        <table class="table-bordered table">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pembuat</th>
                                    <th>Judul</th>
                                    <th>Ringkasan {{ ucfirst($jenis) }}</th>
                                    @if ($jenis == 'curated-journey')
                                        <th>Kategori</th>
                                    @elseif ($jenis == 'ethical')
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Badge</th>
                                    @endif
                                    <th>Gambar</th>
                                    <th>Tanggal Publikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($konten as $index => $item)
                                    <tr>
                                        <td>{{ $konten->firstItem() + $index }}</td>
                                        <td>
                                            {{ $item->user->pegawai->nama ?? 'Tidak Diketahui' }}
                                        </td>
                                        <td>{{ $item->judul ?? '-' }}</td>
                                        <td>{!! Str::limit(strip_tags($item->ringkasan ?? '-'), 100) !!}</td>
                                        @if ($jenis == 'curated-journey')
                                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                        @elseif ($jenis == 'ethical')
                                            <td>{{ $item->duration ?? '-' }}</td>
                                            <td>{{ $item->price ?? '-' }}</td>
                                            <td>{{ $item->badge ?? '-' }}</td>
                                        @endif
                                        <td>
                                            @if ($item->gambar)
                                                <img src="{{ asset( $item->gambar) }}"
                                                    alt="{{ $item->judul }}" width="100">
                                            @else
                                                Tidak Ada Gambar
                                            @endif
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_publikasi)->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('blog.show', ['jenis' => $item->jenis_konten, 'slug' => $item->slug]) }}"
                                                class="btn btn-info btn-sm">Lihat</a>

                                            <a href="{{ route('konten.edit', ['jenis' => $jenis, 'slug' => $item->slug]) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form
                                                action="{{ route('konten.destroy', ['jenis' => $jenis, 'id_konten' => $item->id_konten]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $jenis == 'ethical' ? 10 : ($jenis == 'curated-journey' ? 8 : 7) }}"
                                            class="py-4 text-center">
                                            <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                            <p class="mb-0">Belum ada data {{ $jenis }}.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <section id="blog-pagination" class="blog-pagination section">
                        <div class="container">
                            <ul class="pagination justify-content-end">
                                {{ $konten->links('pagination::custom') }}
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
