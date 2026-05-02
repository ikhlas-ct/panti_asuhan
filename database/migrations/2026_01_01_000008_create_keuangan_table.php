<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panti_asuhan_id')->constrained('panti_asuhan')->onDelete('cascade');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->string('kategori','50')->nullable();
            $table->string('keterangan','255')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->foreignId('donasi_id')->nullable()->constrained('donasi')->onDelete('set null');
            $table->string('bukti', '255')->nullable();
            $table->timestamps();
         
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
