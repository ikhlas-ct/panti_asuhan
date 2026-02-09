<?php

namespace App\Http\Controllers\Service;

use App\Models\Service;
use App\Models\Kategori;
use App\Models\ServiceStep;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class ServiceController extends Controller
{
    public function index(Request $request, $type)
    {
        if (!in_array($type, ['layanan', 'tema', 'transportasi', 'etika', 'keunggulan', 'informasi'])) {
            abort(404);
        }

        $search = $request->input('search');

        $query = Service::where('type', $type)
            ->with('kategori')
            ->withCount('steps');   // agar steps->count() lebih efisien

        if ($search) {
            $query->where(function ($q) use ($search, $type) {
                // Selalu cari di title
                $q->where('title', 'LIKE', "%{$search}%");

                // Description hanya untuk tipe yang punya
                if (in_array($type, ['tema', 'layanan', 'etika', 'keunggulan', 'transportasi'])) {
                    $q->orWhere('description', 'LIKE', "%{$search}%");
                }

                // Price hanya untuk transportasi
                if ($type === 'transportasi') {
                    $q->orWhere('price', 'LIKE', "%{$search}%");
                }

            });
        }

        $services = $query->orderBy('order')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('pages.service.index', compact('services', 'search', 'type'));
    }

    public function create($type)
    {
        if (!in_array($type, ['layanan', 'tema', 'transportasi', 'etika', 'keunggulan', 'informasi'])) {
            abort(404);
        }

        $kategoris = Kategori::all();

        return view('pages.service.create', compact('type', 'kategoris'));
    }

    public function store(Request $request, $type)
    {
        if (!in_array($type, ['layanan', 'tema', 'transportasi', 'etika', 'keunggulan', 'informasi'])) {
            abort(404);
        }

        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|exists:kategori,id_kategori',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10000',
            'price'       => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
            'steps'       => 'nullable|array',
            'steps.*.step_number' => 'required|integer|min:1',
            'steps.*.title'       => 'required|string|max:255',
            'steps.*.icon'        => 'nullable|exists:kategori,id_kategori',
        ];

        $validated = $request->validate($rules);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('service', 'public');
        }

        // Generate unique order per type
        $order = $this->generateUniqueOrder($type, $validated['order'] ?? null);

        $service = Service::create([
            'type'        => $type,
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'gambar'      => $gambarPath,
            'price'       => $validated['price'] ?? null,
            'icon'        => $validated['icon'] ?? null,
            'order'       => $order,
        ]);

        // Proses steps → selalu mulai dari 1 tanpa celah
        $this->saveSteps($service->id, $validated['steps'] ?? []);

        return redirect()->route('service.index', $type)
            ->with('success', ucfirst($type) . ' berhasil ditambahkan!');
    }

    public function edit($type, $id)
    {
        if (!in_array($type, ['layanan', 'tema', 'transportasi', 'etika', 'keunggulan', 'informasi'])) {
            abort(404);
        }

        $service = Service::with(['steps', 'kategori'])->where('type', $type)->findOrFail($id);
        $kategoris = Kategori::all();

        return view('pages.service.edit', compact('service', 'type', 'kategoris'));
    }

    public function update(Request $request, $type, $id)
    {
        if (!in_array($type, ['layanan', 'tema', 'transportasi', 'etika', 'keunggulan', 'informasi'])) {
            abort(404);
        }

        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|exists:kategori,id_kategori',
            'price'       => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10000',
            'steps'       => 'nullable|array',
            'steps.*.step_number' => 'required|integer|min:1',
            'steps.*.title'       => 'required|string|max:255',
            'steps.*.icon'        => 'nullable|exists:kategori,id_kategori',
        ];

        $validated = $request->validate($rules);

        $service = Service::where('type', $type)->findOrFail($id);

        $gambarPath = $service->gambar;
        if ($request->hasFile('gambar')) {
            if ($service->gambar && Storage::disk('public')->exists($service->gambar)) {
                Storage::disk('public')->delete($service->gambar);
            }
            $gambarPath = $request->file('gambar')->store('service', 'public');
        }

        // Generate unique order per type
        $order = $this->generateUniqueOrder($type, $validated['order'] ?? null, $service->id);

        $service->update([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'icon'        => $validated['icon'] ?? null,
            'gambar'      => $gambarPath,
            'price'       => $validated['price'] ?? null,
            'order'       => $order,
        ]);

        // Hapus steps lama dan simpan yang baru (selalu 1,2,3,... tanpa celah)
        $service->steps()->delete();
        $this->saveSteps($service->id, $validated['steps'] ?? []);

        return redirect()->route('service.index', $type)
            ->with('success', ucfirst($type) . ' berhasil diperbarui!');
    }

    public function destroy($type, $id)
    {
        if (!in_array($type, ['layanan', 'tema', 'transportasi', 'etika', 'keunggulan', 'informasi'])) {
            abort(404);
        }

        $service = Service::where('type', $type)->findOrFail($id);

        if ($service->gambar && Storage::disk('public')->exists($service->gambar)) {
            Storage::disk('public')->delete($service->gambar);
        }

        $service->steps()->delete();
        $service->delete();

        return redirect()->route('service.index', $type)
            ->with('success', ucfirst($type) . ' berhasil dihapus!');
    }


    private function generateUniqueOrder(string $type, $providedOrder = null, $excludeId = null): int
    {
        if ($providedOrder === null || $providedOrder === '') {
            $max = Service::where('type', $type)->max('order') ?? -1;
            return $max + 1;
        }

        $providedOrder = (int) $providedOrder;

        $query = Service::where('type', $type)
            ->where('order', $providedOrder);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            $max = Service::where('type', $type)->max('order') ?? -1;
            return $max + 1;
        }

        return $providedOrder;
    }


    private function saveSteps(int $serviceId, array $stepsData): void
    {
        if (empty($stepsData)) {
            return;
        }

        $sortedSteps = collect($stepsData)
            ->sortBy('step_number')
            ->values();

        foreach ($sortedSteps as $index => $step) {
            ServiceStep::create([
                'service_id'  => $serviceId,
                'step_number' => $index + 1,
                'title'       => $step['title'],
                'icon'        => $step['icon'] ?? null,
            ]);
        }
    }
}
