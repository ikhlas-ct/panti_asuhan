<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donatur_id')->constrained('donatur')->onDelete('cascade');
            $table->foreignId('panti_asuhan_id')->constrained('panti_asuhan')->onDelete('cascade');

            // Jenis & metode
            $table->enum('jenis_donasi', ['uang', 'barang']);
            $table->enum('metode', ['online', 'kunjungan']);
            // online   → donatur transfer/QRIS lalu upload bukti
            // kunjungan → donatur datang langsung, pengurus konfirmasi

            // Data donasi uang
            $table->decimal('nominal', 15, 2)->nullable();
            $table->string('bukti_transfer')->nullable(); // path foto bukti (wajib jika online & uang)

            // Data donasi barang — diisi donatur (deskripsi umum)
            $table->text('deskripsi_barang')->nullable();

      

            // Kunjungan
            $table->date('tanggal_kunjungan')->nullable(); // diisi jika metode kunjungan
            $table->date('tanggal_donasi');                // tanggal donatur input

            // Catatan bebas dari donatur
            $table->text('catatan')->nullable();

            // Status & konfirmasi
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('alasan_tolak')->nullable();          // diisi jika ditolak
            $table->foreignId('dikonfirmasi_oleh')->nullable() // user id yang konfirmasi
                  ->constrained('users')->onDelete('set null');
            $table->timestamp('dikonfirmasi_at')->nullable();  // kapan dikonfirmasi

            $table->timestamps();
         
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};
