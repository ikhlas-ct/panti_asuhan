<?php

use App\Http\Controllers\Landing\LandingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Login\AuthController;

use App\Http\Controllers\Profile\CamatController;
use App\Http\Controllers\Konten\ArtikelController;
use App\Http\Controllers\Gallery\GalleryController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Profile\SettingController;

// =================== Pegawai dan camat ===================
use App\Http\Controllers\Profile\KategoriController;
use App\Http\Controllers\Profile\HeroslideController;
use App\Http\Controllers\Pegawai\ProfilpegawaiController;


Route::get('/ethical-blog', function () {
    return view('pages.mentawaitribe.ethical');
});



Route::get('/blog/{jenis?}', [LandingController::class, 'blog'])->where('jenis', 'artikel|curated-journey')->name('landing.blog');
Route::get('/blog/{jenis}/category/{slug}', [LandingController::class, 'category'])->where('jenis', 'artikel|curated-journey')->name('blog.category');
Route::get('/blog/{jenis}/{slug}', [LandingController::class, 'show'])->where('jenis', 'artikel|curated-journey|ethical')->name('blog.show');

Route::get('/ethical', [LandingController::class, 'ethical'])->name('landing.ethical');
Route::get('/transportasi', [LandingController::class, 'transportasi'])->name('landing.transportasi');

Route::get('/contact', [LandingController::class, 'contact'])->name('landing.contact');



Route::get('/', [LandingController::class, 'index'])->name('landing.index');




// =================== Auth Routes ===================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login_post'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// =================== Authenticated Routes ===================
Route::middleware('auth')->group(function () {
    Route::get('profil', [ProfilpegawaiController::class, 'profil'])->name('pegawai.profil');
    Route::put('profil', [ProfilpegawaiController::class, 'profil_update'])->name('pegawai.profil_update');
    Route::put('password', [ProfilpegawaiController::class, 'password_update'])->name('pegawai.password_update');

    Route::prefix('konten/{jenis}')->group(function () {
        Route::get('/', [ArtikelController::class, 'index'])
            ->name('konten.index')
            ->where('jenis', 'artikel|curated-journey|ethical');

        Route::get('/create', [ArtikelController::class, 'create'])
            ->name('konten.create')
            ->where('jenis', 'artikel|curated-journey|ethical');
        Route::post('/', [ArtikelController::class, 'store'])
            ->name('konten.store')
            ->where('jenis', 'artikel|curated-journey|ethical');

        Route::get('/{slug}/edit', [ArtikelController::class, 'edit'])
            ->name('konten.edit')
            ->where('jenis', 'artikel|curated-journey|ethical');

        Route::put('/{id_konten}', [ArtikelController::class, 'update'])
            ->name('konten.update')
            ->where('jenis', 'artikel|curated-journey|ethical');

        Route::delete('/{id_konten}', [ArtikelController::class, 'destroy'])
            ->name('konten.destroy')
            ->where('jenis', 'artikel|curated-journey|ethical');
    });

    // Route upload & delete image tetap shared (tidak perlu constraint jenis)
    Route::post('/blog/upload', [ArtikelController::class, 'uploadImage'])->name('blog.upload.image');
    Route::post('/blog/imagedelete', [ArtikelController::class, 'deleteImage'])->name('blog.delete.image');




    // Settings
    Route::prefix('setting')->group(function () {
        Route::get('heroslide', [HeroslideController::class, 'index'])->name('camat.settings.heroslide');
        Route::post('heroslide', [HeroslideController::class, 'store'])->name('camat.settings.heroslide.store');
        Route::get('heroslide/{id}/edit', [HeroslideController::class, 'edit'])->name('camat.settings.heroslide.edit');
        Route::put('heroslide/{id}', [HeroslideController::class, 'update'])->name('camat.settings.heroslide.update');
        Route::delete('heroslide/{id}', [HeroslideController::class, 'destroy'])->name('camat.settings.heroslide.destroy');

        Route::get('/', [SettingController::class, 'edit'])->name('camat.settings.edit');
        Route::put('/', [SettingController::class, 'update'])->name('camat.settings.update');

        Route::get('pengantar', [SettingController::class, 'pengantar'])->name('camat.pengantar');
        Route::put('pengantar/update', [SettingController::class, 'pengantar_update'])->name('camat.pengantar.update');
    });


    // Master Data
    Route::get('/team', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/team/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/team', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/team/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/team/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/team/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');


    Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');

    Route::resource('gallery', GalleryController::class)->except(['show']);

    Route::get('/dashboard', [CamatController::class, 'dashboard'])->name('dashboard');

    Route::prefix('service/{type}')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])
            ->name('service.index')
            ->where('type', 'layanan|tema|transportasi|etika|keunggulan|informasi');

        Route::get('/create', [ServiceController::class, 'create'])
            ->name('service.create')
            ->where('type', 'layanan|tema|transportasi|etika|keunggulan|informasi');
        Route::post('/', [ServiceController::class, 'store'])
            ->name('service.store')
            ->where('type', 'layanan|tema|transportasi|etika|keunggulan|informasi');

        Route::get('/{id}/edit', [ServiceController::class, 'edit'])
            ->name('service.edit')
            ->where('type', 'layanan|tema|transportasi|etika|keunggulan|informasi');

        Route::put('/{id}', [ServiceController::class, 'update'])
            ->name('service.update')
            ->where('type', 'layanan|tema|transportasi|etika|keunggulan|informasi');

        Route::delete('/{id}', [ServiceController::class, 'destroy'])
            ->name('service.destroy')
            ->where('type', 'layanan|tema|transportasi|etika|keunggulan');
    });
});
