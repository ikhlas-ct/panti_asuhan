<?php

namespace App\Http\Controllers\Landing;

use App\Models\Konten;
use App\Models\Gallery;
use App\Models\Pegawai;
use App\Models\Service;
use App\Models\Kategori;
use App\Models\Heroslide;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function index()
    {
        // Hero Slides
        $heroSlides = Heroslide::orderBy('id')->get();

        // Gallery
        $galleries = Gallery::orderBy('id')->get(); // atau orderBy('created_at', 'desc') jika ingin terbaru

        // Team Members (Pegawai)
        $teamMembers = Pegawai::orderBy('id_pegawai')->get(); // atau orderBy('posisi') jika ada urutan posisi

        // Services (layanan & tema), dengan steps
        $services = Service::with('steps')->orderBy('order')->get();

        // Activities dari Konten (jenis_konten = 'curated-journey'), limit 3 agar sesuai carousel
        $activities = Konten::where('jenis_konten', 'curated-journey')
            ->inRandomOrder()
            ->limit(3)
            ->get();



        $settings = WebsiteSetting::getInstance();

        return view('pages.mentawaitribe.index', compact(
            'heroSlides',
            'galleries',
            'teamMembers',
            'services',
            'activities',
            'settings'
        ));
    }

    public function blog($jenis = 'artikel')
    {
        // Validasi jenis
        if (!in_array($jenis, ['artikel', 'curated-journey'])) {
            abort(404); // Atau redirect ke default
        }

        // Ambil konten berdasarkan jenis_konten
        $kontens = Konten::where('jenis_konten', $jenis)
            ->orderBy('tanggal_publikasi', 'desc')
            ->paginate(6); // Paginate 6 item per halaman

        $kategoris = Kategori::where('status', true)
            ->whereHas('konten', function ($query) use ($jenis) {
                $query->where('jenis_konten', $jenis);
            })
            ->get();

        // Pass data ke view
        return view('pages.mentawaitribe.blog', compact('kontens', 'kategoris', 'jenis'));
    }

    public function category($jenis, $slug)
    {
        // Validasi jenis
        if (!in_array($jenis, ['artikel', 'curated-journey'])) {
            abort(404);
        }

        $kategori = Kategori::where('slug', $slug)->firstOrFail();


        $kontens = Konten::where('jenis_konten', $jenis)
            ->where('id_kategori', $kategori->id_kategori)
            ->orderBy('tanggal_publikasi', 'desc')
            ->paginate(6);

        $kategoris = Kategori::where('status', true)
            ->whereHas('konten', function ($query) use ($jenis) {
                $query->where('jenis_konten', $jenis);
            })
            ->get();
        return view('pages.mentawaitribe.blog', compact('kontens', 'kategoris', 'jenis', 'kategori'));
    }

    public function show($jenis, $slug)
    {
        // Validasi jenis
        if (!in_array($jenis, ['artikel', 'curated-journey', 'ethical'])) {
            abort(404);
        }

        // Ambil konten
        $konten = Konten::where('jenis_konten', $jenis)
            ->where('slug', $slug)
            ->firstOrFail();

        // === TAMBAHAN VIEWER ===
        $konten->increment('viewer');   // Cara paling simpel & aman di Laravel

        // Hitung estimasi waktu baca
        $readTime = ceil(str_word_count(strip_tags($konten->isi)) / 200);

        // Previous & Next
        $previous = Konten::where('jenis_konten', $jenis)
            ->where('tanggal_publikasi', '<', $konten->tanggal_publikasi)
            ->orderBy('tanggal_publikasi', 'desc')
            ->first();

        $next = Konten::where('jenis_konten', $jenis)
            ->where('tanggal_publikasi', '>', $konten->tanggal_publikasi)
            ->orderBy('tanggal_publikasi', 'asc')
            ->first();

        return view('pages.mentawaitribe.blog-detail', compact(
            'konten',
            'jenis',
            'readTime',
            'previous',
            'next'
        ));
    }


    public function ethical()
    {
        $setting = WebsiteSetting::getInstance();
        $principles = Service::where('type', 'etika')->orderBy('order')->get();

        $benefits = Service::where('type', 'keunggulan')->orderBy('order')->get();
        $ethicals = Konten::where('jenis_konten', 'ethical')->orderBy('tanggal_publikasi', 'desc')->paginate(6);
        if ($ethicals->isEmpty()) {
            Log::info('No ethical content found');
        }


        return view('pages.mentawaitribe.ethical', compact('setting', 'principles', 'benefits', 'ethicals'));
    }

    public function transportasi()
    {
        $setting = WebsiteSetting::getInstance();
        $transportations = Service::where('type', 'transportasi')->orderBy('order')->get();
        $informasis = Service::where('type', 'informasi')->orderBy('order')->get();

        return view('pages.mentawaitribe.transportasi', compact('setting', 'transportations', 'informasis'));
    }

    public function contact()
    {
        $settings = WebsiteSetting::getInstance();
        return view('pages.mentawaitribe.kontak', compact('settings'));
    }

    public function about()
    {
        $setting = WebsiteSetting::getInstance();
        $founder = $setting->karyawan;


        $team = Pegawai::all();
        return view('pages.mentawaitribe.about', compact('setting', 'founder', 'team'));

        }

}
