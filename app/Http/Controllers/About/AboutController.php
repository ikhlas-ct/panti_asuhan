<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function edit()
    {
        $about = WebsiteSetting::getInstance();   // ambil atau buat record pertama
        $pegawais = Pegawai::orderBy('nama')->get();

        return view('pages.about.create', compact('about', 'pegawais'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'about_us'    => 'required',
            'karyawan_id' => 'required|exists:pegawai,id_pegawai',
        ]);

        $about = WebsiteSetting::getInstance();

        $about->updateOrCreate(
            ['id' => $about->id ?? null],
            [
                'about_us'    => $request->about_us,
                'karyawan_id' => $request->karyawan_id,
            ]
        );

        return redirect()->route('about')
            ->with('success', 'About Us berhasil disimpan!');
    }
}
