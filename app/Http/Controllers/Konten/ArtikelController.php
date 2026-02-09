<?php

namespace App\Http\Controllers\Konten;

use App\Models\Konten;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index(Request $request, $jenis)
    {
        // Validasi jenis konten
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $search = $request->input('search');

        // Query utama
        $konten = Konten::with(['user.pegawai', 'kategori'])   // eager loading agar tidak N+1
            ->where('jenis_konten', $jenis)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'LIKE', "%{$search}%")
                        ->orWhere('ringkasan', 'LIKE', "%{$search}%")
                        ->orWhereHas('user.pegawai', function ($qp) use ($search) {
                            $qp->where('nama', 'LIKE', "%{$search}%");   // ← diperbaiki: 'nama' bukan 'nama_pegawai'
                        });
                });
            })
            ->orderBy('tanggal_publikasi', 'desc')   // lebih tepat pakai tanggal_publikasi
            ->paginate(10)
            ->appends(['search' => $search]);

        // Ambil semua kategori (jika memang dibutuhkan di view)
        $kategoris = Kategori::where('status', true)->get();

        return view('pages.artikel.artikel', compact('konten', 'kategoris', 'search', 'jenis'));
    }

    public function create($jenis)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $kategori = Kategori::all()->orderBy('nama_kategori', 'asc');
        return view('pages.artikel.create', compact('kategori', 'jenis'));
    }

    public function store(Request $request, $jenis)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $user = Auth::user();

        $rules = [
            'judul'    => 'required|string|max:255',
            'isi'      => 'required',
            'ringkasan' => 'required|string|max:255',
            'kategori' => 'nullable|exists:kategori,id_kategori',
            'duration' => 'nullable|string|max:255',
            'price'    => 'nullable|string|max:255',
            'badge'    => 'nullable|string|max:255',

            'gambar'   => 'required|image|mimes:jpeg,png,jpg,webp|max:10000',
        ];

        $request->validate($rules);

        $gambarPath = $request->file('gambar')->store('gambar', 'public');

        $originalSlug = Str::slug($request->judul);
        $slug = $originalSlug;
        $counter = 1;
        while (Konten::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $originalJudul = $request->judul;
        $judul = $originalJudul;
        $counter = 1;
        while (Konten::where('judul', $judul)->exists()) {
            $judul = $originalJudul . '-' . $counter++;
        }

        Konten::create([
            'judul'             => $judul,
            'ringkasan'    => $request->ringkasan,
            'isi'               => $request->isi,
            'duration' => $request->duration,
            'price'    => $request->price,
            'badge'    => $request->badge,
            'id_user'           => $user->id,
            'slug'              => $slug,
            'jenis_konten'      => $jenis,
            'id_kategori'       => $request->kategori,
            'gambar'            => $gambarPath,
            'tanggal_publikasi' => now(),
        ]);

        return redirect()->route('konten.index', $jenis)->with('success', ucfirst($jenis) . ' berhasil ditambahkan!');
    }

    public function edit($jenis, $slug)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $kategori = Kategori::all()->orderBy('nama_kategori', 'asc');
        $artikel = Konten::where('jenis_konten', $jenis)->where('slug', $slug)->firstOrFail();
        return view('pages.artikel.artikel_edit', compact('artikel', 'kategori', 'jenis'));
    }

    public function update(Request $request, $jenis, $id_konten)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $rules = [
            'judul'    => 'required|string|max:255',
            'ringkasan' => 'required|string|max:255',
            'isi'      => 'required',
            'duration' => 'nullable|string|max:255',
            'price'    => 'nullable|string|max:255',
            'badge'    => 'nullable|string|max:255',
            'kategori' => 'nullable|exists:kategori,id_kategori',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:12000',
        ];

        $request->validate($rules);

        $artikel = Konten::where('jenis_konten', $jenis)->findOrFail($id_konten);

        $gambarPath = $artikel->gambar;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('gambar', 'public');
        }

        $slug = Str::slug($request->judul);
        $slugCount = Konten::where('slug', 'LIKE', "{$slug}%")
            ->where('id_konten', '!=', $id_konten)
            ->count();
        if ($slugCount) $slug .= '-' . ($slugCount + 1);

        $judul = $request->judul;
        $judulCount = Konten::where('judul', 'LIKE', "{$judul}%")
            ->where('id_konten', '!=', $id_konten)
            ->count();
        if ($judulCount) $judul .= '-' . ($judulCount + 1);

        $artikel->update([
            'judul'       => $judul,
            'ringkasan'    => $request->ringkasan,
            'isi'         => $request->isi,
            'slug'        => $slug,
            'duration'   => $request->duration,
            'price'      => $request->price,
            'badge'      => $request->badge,
            'id_kategori' => $request->kategori,
            'gambar'      => $gambarPath,
        ]);

        return redirect()->route('konten.index', $jenis)->with('success', ucfirst($jenis) . ' berhasil diperbarui!');
    }

    public function destroy($jenis, $id_konten)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $artikel = Konten::where('jenis_konten', $jenis)->findOrFail($id_konten);
        $artikel->delete();

        // Perbaikan: Ganti 'artikel.index' menjadi 'konten.index'
        return redirect()->route('konten.index', $jenis)->with('success', ucfirst($jenis) . ' berhasil dihapus!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $path = $request->file('image')
            ->store('upload', 'public');

        $url = asset("storage/{$path}");

        return response()->json(['url' => $url]);
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'src' => 'required|string',
        ]);

        $urlPath = parse_url($request->src, PHP_URL_PATH);
        $relative = ltrim($urlPath, '/storage/');

        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
