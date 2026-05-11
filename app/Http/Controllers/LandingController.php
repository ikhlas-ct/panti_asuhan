<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PantiAsuhan;
use App\Models\AnakAsuh;
use App\Models\Konten;
use App\Models\Kategori;
use App\Models\HeroSlide;
use App\Models\WebsiteSetting;
use App\Models\Pegawai;
use App\Models\Keuangan;

class LandingController extends Controller
{
    // ══════════════════════════════════════════════════════════
    //  HELPER — ambil setting website (dipakai semua halaman)
    // ══════════════════════════════════════════════════════════
    private function getSetting(): ?WebsiteSetting
    {
        return WebsiteSetting::getSetting();
    }

    // ══════════════════════════════════════════════════════════
    //  HOME  /
    // ══════════════════════════════════════════════════════════
    public function index()
    {
        $setting = $this->getSetting();

        // Hero slides
        $heroSlides = HeroSlide::all();

        // Statistik utama
        $totalPanti   = PantiAsuhan::aktif()->count();
        $totalAnakAsuh = AnakAsuh::where('status', 'aktif')->count();
        $totalKegiatan = Konten::kegiatan()->where('status', 'published')->count();

        // 3 panti asuhan unggulan (aktif, dengan jumlah anak & foto)
        $pantiFeatured = PantiAsuhan::aktif()
            ->with(['fotoPanti' => fn($q) => $q->orderBy('urutan')->limit(1)])
            ->withCount(['anakAsuh' => fn($q) => $q->where('status', 'aktif')])
            ->limit(3)
            ->get();

        // 3 berita / artikel terbaru
        $beritaTerbaru = Konten::berita()
            ->with(['user', 'kategori'])
            ->where('status', 'published')
            ->latest('tanggal_publikasi')
            ->limit(3)
            ->get();

        return view('pages.landing.index', compact(
            'setting',
            'heroSlides',
            'totalPanti',
            'totalAnakAsuh',
            'totalKegiatan',
            'pantiFeatured',
            'beritaTerbaru',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  BERITA  /berita
    // ══════════════════════════════════════════════════════════
    public function berita(Request $request)
    {
        $setting = $this->getSetting();

        // Kategori aktif untuk filter tab
        $kategoris = Kategori::where('status', 'aktif')->get();

        // Berita featured (1 besar + 2 kecil)
        $beritaFeatured = Konten::berita()
            ->with(['user', 'kategori'])
            ->where('status', 'published')
            ->latest('tanggal_publikasi')
            ->first();

        $beritaPopuler = Konten::berita()
            ->with(['user', 'kategori'])
            ->where('status', 'published')
            ->orderByDesc('viewer')
            ->skip(1)
            ->limit(2)
            ->get();

        // Query semua konten (berita + artikel)
        $query = Konten::with(['user', 'kategori'])
            ->where('status', 'published')
            ->whereIn('jenis_konten', ['berita', 'artikel'])
            ->latest('tanggal_publikasi');

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter jenis konten (tab)
        if ($request->filled('jenis') && $request->jenis !== 'semua') {
            $query->where('jenis_konten', $request->jenis);
        }

        // Search
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->q . '%')
                  ->orWhere('ringkasan', 'like', '%' . $request->q . '%');
            });
        }

        $beritaList = $query->paginate(6)->withQueryString();

        return view('pages.landing.berita', compact(
            'setting',
            'kategoris',
            'beritaFeatured',
            'beritaPopuler',
            'beritaList',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  DETAIL BERITA  /berita/{slug}
    // ══════════════════════════════════════════════════════════
    public function beritaDetail(string $slug)
    {
        $setting = $this->getSetting();

        $konten = Konten::with(['user', 'kategori', 'pantiAsuhan'])
            ->whereIn('jenis_konten', ['berita', 'artikel'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Tambah viewer
        $konten->incrementViewer();

        // Artikel terkait (kategori sama, bukan dirinya sendiri)
        $artikelTerkait = Konten::with(['user', 'kategori'])
            ->whereIn('jenis_konten', ['berita', 'artikel'])
            ->where('id_kategori', $konten->id_kategori)
            ->where('id_konten', '!=', $konten->id_konten)
            ->where('status', 'published')
            ->latest('tanggal_publikasi')
            ->limit(3)
            ->get();

        return view('pages.landing.berita-detail', compact(
            'setting',
            'konten',
            'artikelTerkait',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  DAFTAR PANTI  /daftar-panti
    // ══════════════════════════════════════════════════════════
    public function daftarPanti(Request $request)
    {
        $setting = $this->getSetting();

        // Statistik
        $totalPanti    = PantiAsuhan::aktif()->count();
        $totalAnakAsuh = AnakAsuh::where('status', 'aktif')->count();

        // Daftar kecamatan unik untuk filter
        $kecamatanList = PantiAsuhan::aktif()
            ->whereNotNull('kecamatan')
            ->distinct()
            ->pluck('kecamatan');

        // Query panti
        $query = PantiAsuhan::aktif()
            ->with([
                'fotoPanti' => fn($q) => $q->orderBy('urutan')->limit(1),
            ])
            ->withCount(['anakAsuh' => fn($q) => $q->where('status', 'aktif')]);

        // Filter kecamatan
        if ($request->filled('kecamatan') && $request->kecamatan !== 'semua') {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Search nama / alamat
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_panti', 'like', '%' . $request->q . '%')
                  ->orWhere('alamat', 'like', '%' . $request->q . '%')
                  ->orWhere('kecamatan', 'like', '%' . $request->q . '%');
            });
        }

        $pantiList = $query->get();

        // Data koordinat untuk peta Leaflet
        // Catatan: tambahkan kolom latitude & longitude di tabel panti_asuhan
        // jika belum ada, gunakan koordinat default Kota Malang
        $pantiMapData = $pantiList->map(fn($p) => [
            'id'    => $p->id,
            'nama'  => $p->nama_panti,
            'alamat'=> $p->alamat,
            'kec'   => $p->kecamatan,
            'lat'   => $p->latitude  ?? -7.9797,
            'lng'   => $p->longitude ?? 112.6304,
        ]);

        return view('pages.landing.daftar-panti', compact(
            'setting',
            'totalPanti',
            'totalAnakAsuh',
            'kecamatanList',
            'pantiList',
            'pantiMapData',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  DETAIL PANTI  /daftar-panti/{id}
    // ══════════════════════════════════════════════════════════
    public function pantiDetail(int $id)
    {
        $setting = $this->getSetting();

        $panti = PantiAsuhan::aktif()
            ->with([
                'fotoPanti'  => fn($q) => $q->orderBy('urutan'),
                'pengurus'   => fn($q) => $q->aktif(),
                'anakAsuh'   => fn($q) => $q->aktif(),
                'konten'     => fn($q) => $q->kegiatan()
                                            ->where('status', 'published')
                                            ->latest('tanggal_mulai')
                                            ->limit(3),
            ])
            ->withCount(['anakAsuh' => fn($q) => $q->where('status', 'aktif')])
            ->findOrFail($id);

        // Statistik keuangan panti (jika role izinkan tampil publik)
        // Bisa dihapus jika tidak ingin tampil di landing
        $saldoPanti = Keuangan::saldo($panti->id);

        return view('pages.landing.panti-detail', compact(
            'setting',
            'panti',
            'saldoPanti',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  KERJASAMA  /kerjasama
    // ══════════════════════════════════════════════════════════
    public function kerjasama()
    {
        $setting = $this->getSetting();

        // Jadwal kegiatan mendatang (jenis_konten = kegiatan)
        $jadwalKegiatan = Konten::kegiatan()
            ->with(['user', 'pantiAsuhan'])
            ->where('status', 'published')
            ->where('tanggal_mulai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai')
            ->limit(3)
            ->get();

        // Kegiatan yang sudah lewat (opsional, untuk portofolio)
        $kegiatanLalu = Konten::kegiatan()
            ->with(['user', 'pantiAsuhan'])
            ->where('status', 'published')
            ->where('tanggal_mulai', '<', now()->toDateString())
            ->orderByDesc('tanggal_mulai')
            ->limit(3)
            ->get();

        return view('pages.landing.kerjasama', compact(
            'setting',
            'jadwalKegiatan',
            'kegiatanLalu',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  KIRIM PESAN KERJASAMA  /kerjasama (POST)
    // ══════════════════════════════════════════════════════════
    public function kerjasamaKirim(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'no_telp' => 'nullable|string|max:20',
            'email'   => 'required|email',
            'subjek'  => 'required|string|max:100',
            'pesan'   => 'required|string|max:2000',
        ]);

        // Kirim email ke admin (opsional — aktifkan jika mail sudah dikonfigurasi)
        // Mail::to(config('mail.admin_address'))->send(new KerjasamaMail($request->all()));

        // Atau simpan ke database jika ada tabel pesan/kontak
        // PesanMasuk::create($request->only(['nama','no_telp','email','subjek','pesan']));

        return redirect()
            ->route('kerjasama')
            ->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }

    // ══════════════════════════════════════════════════════════
    //  TENTANG  /tentang
    // ══════════════════════════════════════════════════════════
    public function tentang()
    {
        $setting = $this->getSetting();

        // Tim pegawai Dinsos yang tampil publik
        $timPegawai = Pegawai::with('user')
            ->whereNotNull('posisi')
            ->get();

        // Statistik untuk halaman tentang
        $stats = [
            'total_panti'    => PantiAsuhan::aktif()->count(),
            'total_anak'     => AnakAsuh::where('status', 'aktif')->count(),
            'total_kegiatan' => Konten::kegiatan()->where('status', 'published')->count(),
            'total_pengurus' => \App\Models\Pengurus::aktif()->count(),
        ];

        return view('pages.landing.tentang', compact(
            'setting',
            'timPegawai',
            'stats',
        ));
    }
}
