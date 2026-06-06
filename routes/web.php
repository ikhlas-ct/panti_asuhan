<?php

use App\Http\Controllers\AdminPantiDashboardController;
use App\Http\Controllers\AnakAsuhController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\DonaturDashboardController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\KontenController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Login\AuthController;
use App\Http\Controllers\PantiAsuhanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\ProfilDonaturController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilPengurusController;
use App\Http\Controllers\RegisterDonaturController;
use App\Http\Controllers\WebsiteSettingController;
use Illuminate\Support\Facades\Route;












// =================== Auth Routes ===================
Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::post('/login', [AuthController::class, 'login_post'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/asd', [AuthController::class, 'login'])->name('dashboard');
Route::get('/logiasdn', [AuthController::class, 'login'])->name('camat.settings.edit');

Route::get('/register', [RegisterDonaturController::class, 'create'])
    ->name('register.donatur')
    ->middleware('guest');

// Proses simpan registrasi
Route::post('/register', [RegisterDonaturController::class, 'store'])
    ->name('register.donatur.store')
    ->middleware('guest');

Route::get('/',             [LandingController::class, 'index'])->name('home');
Route::get('/berita',       [LandingController::class, 'berita'])->name('berita');
Route::get('/{jenis}/{slug}', [LandingController::class, 'beritaDetail'])
    ->name('berita.detail')
    ->whereIn('jenis', ['berita', 'kegiatan']);
Route::get('/daftar-panti', [LandingController::class, 'daftarPanti'])->name('daftar-panti');
Route::get('/daftar-panti/{id}', [LandingController::class, 'pantiDetail'])->name('panti.detail');
Route::get('/kerjasama',    [LandingController::class, 'kerjasama'])->name('kerjasama');
Route::post('/kerjasama',   [LandingController::class, 'kerjasamaKirim'])->name('kerjasama.kirim');
Route::get('/tentang',      [LandingController::class, 'tentang'])->name('tentang');

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin_panti')->group(function () {
        Route::get('/pengurus/profil', [ProfilPengurusController::class, 'index'])
            ->name('admin_panti.profil');

        Route::put('/pengurus/profil', [ProfilPengurusController::class, 'update'])
            ->name('admin_panti.profil.update');

        Route::put('/pengurus/profil/password', [ProfilPengurusController::class, 'updatePassword'])
            ->name('admin_panti.profil.password');

        Route::post('pengurus/profil/foto',          [ProfilPengurusController::class, 'uploadFoto'])->name('admin_panti.profil.foto');
        Route::get('/admin-panti/dashboard', [AdminPantiDashboardController::class, 'index'])
            ->name('admin_panti.dashboard');
    });
    Route::middleware('role:donatur')->group(function () {
        Route::get('/donatur/dashboard', [DonaturDashboardController::class, 'index'])
            ->name('donatur.dashboard');
        Route::get('donatur/profil',             [ProfilDonaturController::class, 'index'])->name('donatur.profil');
        Route::put('donatur/profil/update',      [ProfilDonaturController::class, 'update'])->name('donatur.profil.update');
        Route::put('donatur/profil/password',    [ProfilDonaturController::class, 'updatePassword'])->name('donatur.profil.password');
        Route::post('donatur/profil/foto',       [ProfilDonaturController::class, 'uploadFoto'])->name('donatur.profil.foto');
    });




    Route::middleware('role:admin_dinsos')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

        Route::get('/dinsos/dashboard', [DashboardController::class, 'dinsosDashboard'])->name('dinsos.dashboard');

        Route::get('/panti-asuhan/create',            [PantiAsuhanController::class, 'create'])->name('panti-asuhan.create');
        Route::post('/panti-asuhan',                  [PantiAsuhanController::class, 'store'])->name('panti-asuhan.store');
        Route::get('/panti-asuhan/{pantiAsuhan}/edit', [PantiAsuhanController::class, 'edit'])->name('panti-asuhan.edit');
        Route::put('/panti-asuhan/{pantiAsuhan}',     [PantiAsuhanController::class, 'update'])->name('panti-asuhan.update');
        Route::delete('/panti-asuhan/{pantiAsuhan}',  [PantiAsuhanController::class, 'destroy'])->name('panti-asuhan.destroy');
        // Hapus foto panti (individual)
        Route::delete('/panti-asuhan/{pantiAsuhan}/foto/{foto}', [PantiAsuhanController::class, 'destroyFoto'])->name('panti-asuhan.foto.destroy');

        Route::get('/pegawai/profil', [ProfileController::class, 'show'])
            ->name('pegawai.profil');
        Route::put('/pegawai/profil', [ProfileController::class, 'update'])
            ->name('pegawai.profil.update');
        Route::put('/pegawai/profil/password', [ProfileController::class, 'updatePassword'])
            ->name('pegawai.profil.password');


        Route::get('/pegawai',                   [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/create',            [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('/pegawai',                  [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('/pegawai/{pegawai}',     [PegawaiController::class, 'show'])->name('pegawai.show');
        Route::get('/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/pegawai/{pegawai}',     [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/pegawai/{pegawai}',  [PegawaiController::class, 'destroy'])->name('pegawai.destroy');


        Route::get('/pengurus',                 [PengurusController::class, 'index'])->name('pengurus.index');
        Route::get('/pengurus/create',          [PengurusController::class, 'create'])->name('pengurus.create');
        Route::post('/pengurus',                [PengurusController::class, 'store'])->name('pengurus.store');
        Route::get('/pengurus/{pengurus}',      [PengurusController::class, 'show'])->name('pengurus.show');
        Route::get('/pengurus/{pengurus}/edit', [PengurusController::class, 'edit'])->name('pengurus.edit');
        Route::put('/pengurus/{pengurus}',      [PengurusController::class, 'update'])->name('pengurus.update');
        Route::delete('/pengurus/{pengurus}',   [PengurusController::class, 'destroy'])->name('pengurus.destroy');


        Route::get('/donatur',                [DonaturController::class, 'index'])->name('donatur.index');
        Route::get('/donatur/create',         [DonaturController::class, 'create'])->name('donatur.create');
        Route::post('/donatur',               [DonaturController::class, 'store'])->name('donatur.store');
        Route::get('/donatur/{donatur}',      [DonaturController::class, 'show'])->name('donatur.show');
        Route::get('/donatur/{donatur}/edit', [DonaturController::class, 'edit'])->name('donatur.edit');
        Route::put('/donatur/{donatur}',      [DonaturController::class, 'update'])->name('donatur.update');
        Route::delete('/donatur/{donatur}',   [DonaturController::class, 'destroy'])->name('donatur.destroy');
        Route::get('donatur-laporan', [DonaturController::class, 'cetakLaporan'])
            ->name('donatur.laporan');

        Route::get('/setting/website',            [WebsiteSettingController::class, 'edit'])->name('setting.website.edit');
        Route::put('/setting/website',            [WebsiteSettingController::class, 'update'])->name('setting.website.update');
        Route::post('/setting/upload-image',      [WebsiteSettingController::class, 'uploadImage'])->name('setting.upload.image');
        Route::post('/setting/delete-image',      [WebsiteSettingController::class, 'deleteImage'])->name('setting.delete.image');
    });

    Route::middleware('role:admin_dinsos,donatur')->group(function () {

        Route::get('/panti-asuhan',                   [PantiAsuhanController::class, 'index'])->name('panti-asuhan.index');
        Route::get('/panti-asuhan/{pantiAsuhan}',     [PantiAsuhanController::class, 'show'])->name('panti-asuhan.show');
    });


    // =================== Authenticated Routes ===================
    Route::middleware('role:admin_dinsos,admin_panti')->group(function () {

        Route::get('/anak-asuh', [AnakAsuhController::class, 'index'])->name('anak-asuh.index');
        Route::get('/anak-asuh/create', [AnakAsuhController::class, 'create'])->name('anak-asuh.create');
        Route::post('/anak-asuh', [AnakAsuhController::class, 'store'])->name('anak-asuh.store');
        Route::get('/anak-asuh/{anakAsuh}', [AnakAsuhController::class, 'show'])->name('anak-asuh.show');
        Route::get('/anak-asuh/{anakAsuh}/edit', [AnakAsuhController::class, 'edit'])->name('anak-asuh.edit');
        Route::put('/anak-asuh/{anakAsuh}', [AnakAsuhController::class, 'update'])->name('anak-asuh.update');
        Route::delete('/anak-asuh/{anakAsuh}', [AnakAsuhController::class, 'destroy'])->name('anak-asuh.destroy');


        Route::get('/konten/{jenis}',            [KontenController::class, 'index'])->name('konten.index');
        // Create & Store
        Route::get('/konten/{jenis}/tambah',      [KontenController::class, 'create'])->name('konten.create');
        Route::post('/konten/{jenis}/simpan',     [KontenController::class, 'store'])->name('konten.store');
        // Edit & Update (berdasarkan slug)
        Route::get('/konten/{jenis}/{slug}/edit', [KontenController::class, 'edit'])->name('konten.edit');
        // Update & Destroy (berdasarkan id_konten)
        Route::put('/konten/{jenis}/{id_konten}', [KontenController::class, 'update'])->name('konten.update');
        Route::delete('/konten/{jenis}/{id_konten}', [KontenController::class, 'destroy'])->name('konten.destroy');

        Route::post('blog/upload-image', [KontenController::class, 'uploadImage'])->name('blog.upload.image');
        Route::post('blog/delete-image', [KontenController::class, 'deleteImage'])->name('blog.delete.image');



        Route::get('/keuangan',                  [KeuanganController::class, 'index'])->name('keuangan.index');
        Route::get('/keuangan/create',           [KeuanganController::class, 'create'])->name('keuangan.create');
        Route::get('/keuangan/laporan',          [KeuanganController::class, 'laporanForm'])->name('keuangan.laporan.form');
        Route::get('/keuangan/laporan/cetak',    [KeuanganController::class, 'laporanCetak'])->name('keuangan.laporan.cetak');
        Route::post('/keuangan',                 [KeuanganController::class, 'store'])->name('keuangan.store');
        Route::get('/keuangan/{keuangan}',       [KeuanganController::class, 'show'])->name('keuangan.show');
        Route::get('/keuangan/{keuangan}/edit',  [KeuanganController::class, 'edit'])->name('keuangan.edit');
        Route::put('/keuangan/{keuangan}',       [KeuanganController::class, 'update'])->name('keuangan.update');
        Route::delete('/keuangan/{keuangan}',    [KeuanganController::class, 'destroy'])->name('keuangan.destroy');

        Route::get('/keuangan-donasi-by-panti',  [KeuanganController::class, 'getDonasiByPanti'])->name('keuangan.donasi-by-panti');
    });


    Route::get('/donasi/print', [DonasiController::class, 'printLaporan'])->name('donasi.print');

    Route::get('/donasi',          [DonasiController::class, 'index'])->name('donasi.index');
    Route::get('/donasi/create',   [DonasiController::class, 'create'])->name('donasi.create');
    Route::post('/donasi',          [DonasiController::class, 'store'])->name('donasi.store');
    Route::get('/donasi/{donasi}', [DonasiController::class, 'show'])->name('donasi.show');
    Route::get('/donasi/{donasi}/edit',   [DonasiController::class, 'edit'])->name('donasi.edit');
    Route::put('/donasi/{donasi}',        [DonasiController::class, 'update'])->name('donasi.update');
    Route::delete('/donasi/{donasi}',        [DonasiController::class, 'destroy'])->name('donasi.destroy');


    // ── Konfirmasi & Tolak (hanya admin & pengurus) ───────────────
    Route::patch('/donasi/{donasi}/konfirmasi', [DonasiController::class, 'konfirmasi'])->name('donasi.konfirmasi');
    Route::patch('/donasi/{donasi}/tolak',      [DonasiController::class, 'tolak'])->name('donasi.tolak');
});
