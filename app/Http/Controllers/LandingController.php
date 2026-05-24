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

        $heroSlides = HeroSlide::all();

        $totalPanti    = PantiAsuhan::aktif()->count();
        $totalAnakAsuh = AnakAsuh::where('status', 'aktif')->count();

        // Status enum kegiatan: direncanakan | berlangsung | selesai | dibatalkan
        $totalKegiatan = Konten::kegiatan()
            ->where('status', '!=', 'dibatalkan')
            ->count();

        $pantiFeatured = PantiAsuhan::aktif()
            ->with(['fotoPanti' => fn($q) => $q->orderBy('urutan')->limit(1)])
            ->withCount(['anakAsuh' => fn($q) => $q->where('status', 'aktif')])
            ->limit(3)
            ->get();

        // 5 berita terbaru — ambil semua tanpa filter status
        $beritaTerbaru = Konten::berita()
            ->with(['user', 'kategori'])
            ->latest('tanggal_publikasi')
            ->limit(5)
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

        // Kategori aktif
        $kategoris = Kategori::where('status', 1)->get();

        // Berita featured: 1 paling baru — ambil semua tanpa filter status
        $beritaFeatured = Konten::berita()
            ->with(['user', 'kategori'])
            ->latest('tanggal_publikasi')
            ->first();

        // 2 berita populer (viewer terbanyak, selain featured)
        $beritaPopuler = Konten::berita()
            ->with(['user', 'kategori'])
            ->when($beritaFeatured, fn($q) => $q->where('id_konten', '!=', $beritaFeatured->id_konten))
            ->orderByDesc('viewer')
            ->limit(2)
            ->get();

        // Grid semua berita — tanpa filter status
        $query = Konten::berita()
            ->with(['user', 'kategori'])
            ->latest('tanggal_publikasi');

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Search: judul, ringkasan, dan isi
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', '%' . $keyword . '%')
                    ->orWhere('ringkasan', 'like', '%' . $keyword . '%')
                    ->orWhere('isi', 'like', '%' . $keyword . '%');
            });
        }

        $beritaList = $query->paginate(9)->withQueryString();

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

        // ── 1. Ambil konten berdasarkan slug — jika tidak ditemukan → 404
        $konten = Konten::with(['user', 'kategori', 'pantiAsuhan'])
            ->where('jenis_konten', 'berita')
            ->where('slug', $slug)
            ->firstOrFail();

        // ── 2. Tambah viewer (hit counter)
        $konten->incrementViewer();

        // ── 3. Berita terkait (kategori sama, bukan dirinya sendiri)
        $artikelTerkait = Konten::with(['user', 'kategori'])
            ->where('jenis_konten', 'berita')
            ->where('id_kategori', $konten->id_kategori)
            ->where('id_konten', '!=', $konten->id_konten)
            ->latest('tanggal_publikasi')
            ->limit(4)
            ->get();

        // ── 4. Navigasi artikel: Sebelumnya & Berikutnya
        $prevKonten = Konten::berita()
            ->where('tanggal_publikasi', '<', $konten->tanggal_publikasi)
            ->latest('tanggal_publikasi')
            ->select('id_konten', 'judul', 'slug', 'gambar', 'tanggal_publikasi')
            ->first();

        $nextKonten = Konten::berita()
            ->where('tanggal_publikasi', '>', $konten->tanggal_publikasi)
            ->oldest('tanggal_publikasi')
            ->select('id_konten', 'judul', 'slug', 'gambar', 'tanggal_publikasi')
            ->first();

        // ── 5. Render view
        return view('pages.landing.berita-detail', compact(
            'setting',
            'konten',
            'artikelTerkait',
            'prevKonten',
            'nextKonten',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  DAFTAR PANTI  /daftar-panti
    // ══════════════════════════════════════════════════════════
    public function daftarPanti(Request $request)
    {
        $setting = $this->getSetting();

        $totalPanti    = PantiAsuhan::aktif()->count();
        $totalAnakAsuh = AnakAsuh::where('status', 'aktif')->count();

        $kecamatanList = PantiAsuhan::aktif()
            ->whereNotNull('kecamatan')
            ->distinct()
            ->pluck('kecamatan');

        $query = PantiAsuhan::aktif()
            ->with(['fotoPanti' => fn($q) => $q->orderBy('urutan')->limit(1)])
            ->withCount(['anakAsuh' => fn($q) => $q->where('status', 'aktif')]);

        if ($request->filled('kecamatan') && $request->kecamatan !== 'semua') {
            $query->where('kecamatan', $request->kecamatan);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_panti', 'like', '%' . $request->q . '%')
                    ->orWhere('alamat', 'like', '%' . $request->q . '%')
                    ->orWhere('kecamatan', 'like', '%' . $request->q . '%');
            });
        }

        $pantiList = $query->get();

        $pantiMapData = $pantiList->map(fn($p) => [
            'id'     => $p->id,
            'nama'   => $p->nama_panti,
            'alamat' => $p->alamat ?? '-',
            'kec'    => $p->kecamatan ?? '-',
            'lat'    => $p->latitude  ?? -7.9797,
            'lng'    => $p->longitude ?? 112.6304,
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
                'anakAsuh'   => fn($q) => $q->where('status', 'aktif'),
                'konten'     => fn($q) => $q->kegiatan()
                    ->where('status', '!=', 'dibatalkan')
                    ->latest('tanggal_mulai')
                    ->limit(3),
            ])
            ->withCount(['anakAsuh' => fn($q) => $q->where('status', 'aktif')])
            ->findOrFail($id);

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

        $jadwalKegiatan = Konten::kegiatan()
            ->with(['user', 'pantiAsuhan'])
            ->whereIn('status', ['direncanakan', 'berlangsung'])
            ->where('tanggal_mulai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai')
            ->limit(3)
            ->get();

        $kegiatanLalu = Konten::kegiatan()
            ->with(['user', 'pantiAsuhan'])
            ->where('status', 'selesai')
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

        $timPegawai = Pegawai::with('user')
            ->whereNotNull('posisi')
            ->get();

        $stats = [
            'total_panti'    => PantiAsuhan::aktif()->count(),
            'total_anak'     => AnakAsuh::where('status', 'aktif')->count(),
            'total_kegiatan' => Konten::kegiatan()->where('status', '!=', 'dibatalkan')->count(),
            'total_pengurus' => \App\Models\Pengurus::aktif()->count(),
        ];

        return view('pages.landing.tentang', compact(
            'setting',
            'timPegawai',
            'stats',
        ));
    }
}
