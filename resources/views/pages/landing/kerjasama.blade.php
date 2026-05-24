<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $setting?->nama ?? 'TitikKebaikan' }} – Kerjasama Kami</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet"/>
</head>
<body>

@include('pages.landing.partials.navbar')

{{-- HERO --}}
<section class="hero-kerjasama">
  <div class="deco-circle" style="width:280px;height:280px;top:-80px;right:-60px;"></div>
  <div class="deco-circle" style="width:140px;height:140px;bottom:-30px;left:60px;background:rgba(224,123,44,.06);"></div>
  <div class="container">
    <div class="section-badge fade-up">Kerja Sama</div>
    <h1 class="fade-up delay-1">Wujudkan <span>Harapan</span> Bersama<br>Anak-Anak Panti Asuhan</h1>
    <p class="lead fade-up delay-2">Kolaborasi dengan kami untuk menciptakan perubahan nyata melalui program amal, donasi, atau kemitraan strategis.</p>
    <div class="fade-up delay-3">
      <a href="#hubungi" class="btn-primary-main"><i class="bi bi-binoculars-fill"></i> Jelajahi Peluang</a>
    </div>
  </div>
</section>

{{-- JENIS KOLABORASI (statis — konten tetap) --}}
<section class="py-5" style="background:var(--white);">
  <div class="container py-4">
    <div class="text-center mb-5 fade-up">
      <h2 class="section-title">Jenis <span>Kolaborasi</span></h2>
      <p class="section-sub">Pelajari berbagai cara untuk berkontribusi dan mendukung anak-anak panti asuhan.</p>
    </div>
    <div class="row g-4">
      <div class="col-6 col-md-3 fade-up delay-1">
        <div class="kolaborasi-card">
          <div class="kol-icon"><i class="bi bi-people-fill"></i></div>
          <div class="kol-title">Komunitas</div>
          <p class="kol-text">Adakan kegiatan seru seperti pelatihan atau acara amal bersama anak-anak panti.</p>
        </div>
      </div>
      <div class="col-6 col-md-3 fade-up delay-2">
        <div class="kolaborasi-card">
          <div class="kol-icon"><i class="bi bi-building"></i></div>
          <div class="kol-title">Perusahaan</div>
          <p class="kol-text">Dukung melalui program CSR, donasi, atau sponsor untuk pendidikan anak.</p>
        </div>
      </div>
      <div class="col-6 col-md-3 fade-up delay-3">
        <div class="kolaborasi-card">
          <div class="kol-icon"><i class="bi bi-bank"></i></div>
          <div class="kol-title">Pemerintah</div>
          <p class="kol-text">Berkolaborasi dalam program pemberdayaan atau dukungan kebijakan.</p>
        </div>
      </div>
      <div class="col-6 col-md-3 fade-up delay-4">
        <div class="kolaborasi-card">
          <div class="kol-icon"><i class="bi bi-person-heart"></i></div>
          <div class="kol-title">Individu</div>
          <p class="kol-text">Jadi relawan, mentor, atau donatur untuk membantu kebutuhan harian anak-anak.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- JADWAL KEGIATAN --}}
@if($jadwalKegiatan->isNotEmpty())
<section class="py-5" style="background:var(--cream);">
  <div class="container py-4">
    <div class="text-center mb-5 fade-up">
      <h2 class="section-title">Jadwal <span>Kegiatan</span></h2>
      <p class="section-sub">Ikuti acara kami untuk mendukung dan berpartisipasi dalam kegiatan amal.</p>
    </div>
    <div class="row g-4">
      @foreach($jadwalKegiatan as $kegiatan)
      <div class="col-md-4 fade-up delay-{{ $loop->iteration }}">
        <div class="event-card">
          <div class="event-date">
            <i class="bi bi-calendar3"></i>
            {{ $kegiatan->tanggal_mulai?->translatedFormat('l, d F Y') }}
          </div>
          <div class="event-title">{{ $kegiatan->judul }}</div>
          <div class="event-meta">
            @if($kegiatan->penanggung_jawab)
              <span><i class="bi bi-person-fill"></i> <strong style="color:var(--orange);">{{ $kegiatan->penanggung_jawab }}</strong></span>
            @endif
            @if($kegiatan->lokasi)
              <span><i class="bi bi-geo-alt-fill"></i> {{ $kegiatan->lokasi }}</span>
            @endif
          </div>
          @if($kegiatan->tanggal_mulai && $kegiatan->tanggal_selesai)
            <div class="event-time">
              <i class="bi bi-clock-fill"></i>
              {{ $kegiatan->tanggal_mulai->format('H.i') }} – {{ $kegiatan->tanggal_selesai->format('H.i') }}
            </div>
          @endif
          <p class="event-desc">{{ $kegiatan->ringkasan ?? $kegiatan->isi }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- HUBUNGI KAMI --}}
<section id="hubungi" class="contact-section">
  <div class="container">
    <div class="text-center mb-5 fade-up">
      <h2 class="section-title">Hubungi <span>Kami</span></h2>
      <p class="section-sub">Mari berkolaborasi untuk mewujudkan perubahan positif bagi anak-anak panti asuhan.</p>
    </div>

    {{-- Flash success --}}
    @if(session('success'))
    <div class="alert alert-success text-center mb-4" style="border-radius:var(--radius);border:none;background:var(--green-pale);color:var(--green-dark);font-weight:600;">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="row g-4 align-items-start">
      {{-- Kontak info dari setting --}}
      <div class="col-lg-5 fade-up delay-1">
        @if($setting?->email)
        <div class="contact-info-item">
          <div class="ci-box green"><i class="bi bi-envelope-fill"></i></div>
          <div><div class="ci-label">Email</div><div class="ci-value">{{ $setting->email }}</div></div>
        </div>
        @endif
        @if($setting?->nomor_telepon)
        <div class="contact-info-item">
          <div class="ci-box orange"><i class="bi bi-telephone-fill"></i></div>
          <div><div class="ci-label">Telepon</div><div class="ci-value">{{ $setting->nomor_telepon }}</div></div>
        </div>
        @endif
        @if($setting?->alamat)
        <div class="contact-info-item">
          <div class="ci-box dark"><i class="bi bi-geo-alt-fill"></i></div>
          <div><div class="ci-label">Alamat</div><div class="ci-value">{{ $setting->alamat }}</div></div>
        </div>
        @endif
      </div>

      {{-- Form --}}
      <div class="col-lg-7 fade-up delay-2">
        <div class="form-card">
          <form method="POST" action="{{ route('kerjasama.kirim') }}">
            @csrf
            <div class="row g-3">
              <div class="col-12">
                <div class="form-label-custom">Nama Lengkap</div>
                <input type="text" name="nama" class="form-control-custom" placeholder="Nama Lengkap Anda"
                  value="{{ old('nama') }}" required/>
                @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
              </div>
              <div class="col-12">
                <div class="form-label-custom">Nomor Telepon</div>
                <input type="text" name="no_telp" class="form-control-custom" placeholder="Nomor Telepon Anda"
                  value="{{ old('no_telp') }}"/>
              </div>
              <div class="col-12">
                <div class="form-label-custom">Email</div>
                <input type="email" name="email" class="form-control-custom" placeholder="Email Anda"
                  value="{{ old('email') }}" required/>
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
              </div>
              <div class="col-12">
                <div class="form-label-custom">Subjek Pesan</div>
                <select name="subjek" class="form-control-custom" required>
                  <option value="">Pilih Salah Satu</option>
                  <option value="Donasi" {{ old('subjek') === 'Donasi' ? 'selected' : '' }}>Donasi</option>
                  <option value="Kerjasama CSR" {{ old('subjek') === 'Kerjasama CSR' ? 'selected' : '' }}>Kerjasama CSR</option>
                  <option value="Volunteer" {{ old('subjek') === 'Volunteer' ? 'selected' : '' }}>Volunteer</option>
                  <option value="Informasi Umum" {{ old('subjek') === 'Informasi Umum' ? 'selected' : '' }}>Informasi Umum</option>
                </select>
                @error('subjek')<small class="text-danger">{{ $message }}</small>@enderror
              </div>
              <div class="col-12">
                <div class="form-label-custom">Pesan</div>
                <textarea name="pesan" class="form-control-custom" placeholder="Tulis Pesan Kepada Kami" required>{{ old('pesan') }}</textarea>
                @error('pesan')<small class="text-danger">{{ $message }}</small>@enderror
              </div>
              <div class="col-12">
                <button type="submit" class="btn-send">Kirim Pesan</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- FAQ --}}
<section class="faq-section">
  <div class="container" style="max-width:660px;">
    <div class="text-center mb-5 fade-up">
      <div class="faq-icon"><i class="bi bi-chat-dots-fill"></i></div>
      <h2 class="section-title">Punya <span>Pertanyaan?</span></h2>
      <p class="section-sub">Temukan jawaban atas pertanyaan yang sering diajukan untuk membantu Anda berkolaborasi dengan kami.</p>
    </div>
    <div class="fade-up delay-1">
      <div class="faq-item">
        <button class="faq-question open" onclick="toggleFaq(this)">
          Apa itu {{ $setting?->nama ?? 'TitikKebaikan' }}?
          <i class="bi bi-chevron-down faq-icon-arrow"></i>
        </button>
        <div class="faq-answer open">
          {{ $setting?->about_us ?? 'TitikKebaikan adalah platform digital yang menghubungkan para donatur, relawan, dan komunitas dengan panti asuhan. Kami hadir untuk memudahkan penyaluran bantuan secara transparan dan terverifikasi.' }}
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-question" onclick="toggleFaq(this)">
          Bagaimana cara mendaftar sebagai donatur?
          <i class="bi bi-chevron-down faq-icon-arrow"></i>
        </button>
        <div class="faq-answer">Anda dapat mendaftar melalui formulir di atas atau menghubungi kami langsung via email. Setelah pendaftaran, tim kami akan menghubungi Anda untuk langkah selanjutnya.</div>
      </div>
      <div class="faq-item">
        <button class="faq-question" onclick="toggleFaq(this)">
          Apakah data panti diverifikasi?
          <i class="bi bi-chevron-down faq-icon-arrow"></i>
        </button>
        <div class="faq-answer">Ya, seluruh data panti asuhan yang terdaftar telah melalui proses verifikasi oleh tim kami. Kami memastikan setiap informasi akurat dan dapat dipertanggungjawabkan.</div>
      </div>
    </div>
  </div>
</section>

{{-- FOOTER --}}
@include('pages.landing.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('landing/js/shared.js') }}"></script>
<script>
  function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const isOpen = btn.classList.contains('open');
    document.querySelectorAll('.faq-question').forEach(q => {
      q.classList.remove('open');
      q.nextElementSibling.classList.remove('open');
    });
    if (!isOpen) { btn.classList.add('open'); answer.classList.add('open'); }
  }
</script>
</body>
</html>
