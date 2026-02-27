@extends('layouts.user.user')

@section('title', 'About Us')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">About Us</div>
                </div>
                <div class="card-body">
                    @include('partials.alert.alert')

                    <form action="{{ route('admin.about.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Karyawan -->
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Pilih Founder</label>
                                <select name="karyawan_id" class="form-select" required>
                                    <option value="" disabled>Pilih Founder</option>
                                    @foreach($pegawais as $p)
                                        <option value="{{ $p->id_pegawai }}"
                                            {{ ($about->karyawan_id ?? old('karyawan_id')) == $p->id_pegawai ? 'selected' : '' }}>
                                            {{ $p->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Summernote About Us -->
                        <div class="mt-4">
                            <label class="form-label">Isi About Us</label>
                            <textarea class="form-control summernote" name="about_us" rows="12" required>
                                {{ old('about_us', $about->about_us ?? '') }}
                            </textarea>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Simpan About Us</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('pages.artikel.blog_summernote')
@endsection
