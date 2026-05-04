<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi_barang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('donasi_id')
                  ->constrained('donasi')
                  ->onDelete('cascade'); // hapus donasi → semua itemnya ikut terhapus

            // Detail barang — diisi pengurus setelah menerima
            $table->string('nama_barang', 100);
            $table->integer('jumlah_barang');
            $table->string('satuan_barang', 50)->nullable(); // unit, kg, lusin, pcs, dll
            $table->string('foto_barang', 255)->nullable();  // foto per item (opsional)
            $table->text('keterangan')->nullable();          // catatan tambahan per item

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi_barang');
    }
};
