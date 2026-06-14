<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PantiAsuhan;
use App\Models\AnakAsuh;
use App\Models\Konten;
use App\Models\Kategori;
use App\Models\HeroSlide;
use App\Models\Pegawai;
use App\Models\Keuangan;

class LandingController extends Controller
{
    // CATATAN: $settings sudah di-inject global oleh View::composer di
    // AppServiceProvider, jadi tidak perlu melempar $setting dari sini.

    // ══════════════════════════════════════════════════════════
    //  HOME  /
    // ══════════════════════════════════════════════════════════
    public function index()
    {
        $heroSlides = HeroSlide::all();

        $totalPanti    = PantiAsuhan::aktif()->count();
        $totalAnakAsuh = AnakAsuh::where('status', 'aktif')->count();

        $totalKegiatan = Konten::kegiatan()
            ->where('status', '!=', 'dibatalkan')
            ->count();

        $pantiFeatured = PantiAsuhan::aktif()
            ->with(['fotoPanti' => fn($q) => $q->orderBy('urutan')->limit(1)])
            ->withCount(['anakAsuh' => fn($q) => $q->where('status', 'aktif')])
            ->limit(3)
            ->get();

        $beritaTerbaru = Konten::berita()
            ->with(['user', 'kategori'])
            ->latest('tanggal_publikasi')
            ->limit(5)
            ->get();

        return view('pages.landing.index', compact(
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
        $kategoris = Kategori::where('status', 1)->get();

        $beritaFeatured = Konten::berita()
            ->with(['user', 'kategori'])
            ->latest('tanggal_publikasi')
            ->first();

        $beritaPopuler = Konten::berita()
            ->with(['user', 'kategori'])
            ->when($beritaFeatured, fn($q) => $q->where('id_konten', '!=', $beritaFeatured->id_konten))
            ->orderByDesc('viewer')
            ->limit(2)
            ->get();

        $query = Konten::berita()
            ->with(['user', 'kategori'])
            ->latest('tanggal_publikasi');

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

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
            'kategoris',
            'beritaFeatured',
            'beritaPopuler',
            'beritaList',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  DETAIL BERITA / KEGIATAN  /{jenis}/{slug}
    // ══════════════════════════════════════════════════════════
    public function beritaDetail(string $jenis, string $slug)
    {
        $konten = Konten::with(['user', 'kategori', 'pantiAsuhan'])
            ->where('jenis_konten', $jenis)
            ->where('slug', $slug)
            ->firstOrFail();

        $konten->incrementViewer();

        $artikelTerkait = Konten::with(['user', 'kategori'])
            ->where('jenis_konten', $jenis)
            ->where('id_kategori', $konten->id_kategori)
            ->where('id_konten', '!=', $konten->id_konten)
            ->latest('tanggal_publikasi')
            ->limit(4)
            ->get();

        $dateCol = $jenis === 'kegiatan' ? 'tanggal_mulai' : 'tanggal_publikasi';
        $dateVal = $jenis === 'kegiatan' ? $konten->tanggal_mulai : $konten->tanggal_publikasi;

        $prevKonten = Konten::where('jenis_konten', $jenis)
            ->where($dateCol, '<', $dateVal)
            ->latest($dateCol)
            ->select('id_konten', 'judul', 'slug', 'gambar', 'tanggal_publikasi', 'tanggal_mulai')
            ->first();

        $nextKonten = Konten::where('jenis_konten', $jenis)
            ->where($dateCol, '>', $dateVal)
            ->oldest($dateCol)
            ->select('id_konten', 'judul', 'slug', 'gambar', 'tanggal_publikasi', 'tanggal_mulai')
            ->first();

        return view('pages.landing.berita-detail', compact(
            'konten',
            'jenis',
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
            'panti',
            'saldoPanti',
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  KERJASAMA  /kerjasama
    // ══════════════════════════════════════════════════════════
    public function kerjasama()
    {
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
            'timPegawai',
            'stats',
        ));
    }
}
