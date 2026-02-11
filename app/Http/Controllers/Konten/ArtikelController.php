<?php

namespace App\Http\Controllers\Konten;

use App\Models\Konten;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ArtikelController extends Controller
{
    public function index(Request $request, $jenis)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $search = $request->input('search');

        $konten = Konten::with(['user.pegawai', 'kategori'])
            ->where('jenis_konten', $jenis)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'LIKE', "%{$search}%")
                        ->orWhere('ringkasan', 'LIKE', "%{$search}%")
                        ->orWhereHas('user.pegawai', function ($qp) use ($search) {
                            $qp->where('nama', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->orderBy('tanggal_publikasi', 'desc')
            ->paginate(10)
            ->appends(['search' => $search]);

        $kategoris = Kategori::where('status', true)->get();

        return view('pages.artikel.artikel', compact('konten', 'kategoris', 'search', 'jenis'));
    }

    public function create($jenis)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('pages.artikel.create', compact('kategori', 'jenis'));
    }

    public function store(Request $request, $jenis)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $user = Auth::user();

        $rules = [
            'judul'     => 'required|string|max:255',
            'isi'       => 'required',
            'ringkasan' => 'required|string|max:255',
            'kategori'  => 'nullable|exists:kategori,id_kategori',
            'duration'  => 'nullable|string|max:255',
            'price'     => 'nullable|string|max:255',
            'badge'     => 'nullable|string|max:255',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg,webp|max:10000',
        ];

        $request->validate($rules);

        // Upload gambar ke folder public/gambar
        $folder = 'gambar';
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        $image = $request->file('gambar');
        $filename = $image->hashName();
        $image->move(public_path($folder), $filename);

        $gambarPath = $folder . '/' . $filename;

        // Generate unique slug & judul
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
            'ringkasan'         => $request->ringkasan,
            'isi'               => $request->isi,
            'duration'          => $request->duration,
            'price'             => $request->price,
            'badge'             => $request->badge,
            'id_user'           => $user->id,
            'slug'              => $slug,
            'jenis_konten'      => $jenis,
            'id_kategori'       => $request->kategori,
            'gambar'            => $gambarPath,
            'tanggal_publikasi' => now(),
        ]);

        return redirect()->route('konten.index', $jenis)
            ->with('success', ucfirst($jenis) . ' berhasil ditambahkan!');
    }

    public function edit($jenis, $slug)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        $artikel = Konten::where('jenis_konten', $jenis)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.artikel.artikel_edit', compact('artikel', 'kategori', 'jenis'));
    }

    public function update(Request $request, $jenis, $id_konten)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $rules = [
            'judul'     => 'required|string|max:255',
            'ringkasan' => 'required|string|max:255',
            'isi'       => 'required',
            'duration'  => 'nullable|string|max:255',
            'price'     => 'nullable|string|max:255',
            'badge'     => 'nullable|string|max:255',
            'kategori'  => 'nullable|exists:kategori,id_kategori',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:12000',
        ];

        $request->validate($rules);

        $artikel = Konten::where('jenis_konten', $jenis)->findOrFail($id_konten);

        $gambarPath = $artikel->gambar;

        if ($request->hasFile('gambar')) {
            $folder = 'gambar';
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $image = $request->file('gambar');
            $filename = $image->hashName();
            $image->move(public_path($folder), $filename);

            $gambarPath = $folder . '/' . $filename;

            // Hapus gambar lama
            if ($artikel->gambar && file_exists(public_path($artikel->gambar))) {
                unlink(public_path($artikel->gambar));
            }
        }

        // Update slug & judul unik
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
            'ringkasan'   => $request->ringkasan,
            'isi'         => $request->isi,
            'slug'        => $slug,
            'duration'    => $request->duration,
            'price'       => $request->price,
            'badge'       => $request->badge,
            'id_kategori' => $request->kategori,
            'gambar'      => $gambarPath,
        ]);

        return redirect()->route('konten.index', $jenis)
            ->with('success', ucfirst($jenis) . ' berhasil diperbarui!');
    }

    public function destroy($jenis, $id_konten)
    {
        if (!in_array($jenis, ['artikel', 'aktivitas', 'ethical'])) {
            abort(404);
        }

        $artikel = Konten::where('jenis_konten', $jenis)->findOrFail($id_konten);

        // Hapus gambar jika ada
        if ($artikel->gambar && file_exists(public_path($artikel->gambar))) {
            unlink(public_path($artikel->gambar));
        }

        $artikel->delete();

        return redirect()->route('konten.index', $jenis)
            ->with('success', ucfirst($jenis) . ' berhasil dihapus!');
    }

    // ================== UPLOAD IMAGE UNTUK EDITOR (CKEditor/TinyMCE) ==================
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $folder = 'upload';
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        $image = $request->file('image');
        $filename = $image->hashName();
        $image->move(public_path($folder), $filename);

        $path = $folder . '/' . $filename;

        return response()->json(['url' => asset($path)]);
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'src' => 'required|string',
        ]);

        $urlPath = parse_url($request->src, PHP_URL_PATH);
        $relative = ltrim($urlPath, '/');   // contoh: upload/abc123.jpg

        $fullPath = public_path($relative);

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
