<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konten', function (Blueprint $table) {
            $table->id('id_konten');
            $table->string('judul',255)->unique();
            $table->longText('isi');
            $table->string('ringkasan', 255)->nullable();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->timestamp('tanggal_publikasi')->useCurrent();
            $table->enum('jenis_konten', ['artikel','kegiatan','berita']);
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->nullOnDelete();
            $table->string('gambar');
            $table->integer('viewer')->default(0);
            // untuk kegiatan
            $table->foreignId('panti_asuhan_id')
                ->nullable()
                ->constrained('panti_asuhan')
                ->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi', '100')->nullable();
            $table->enum('status', ['direncanakan', 'berlangsung', 'selesai', 'dibatalkan'])->default('direncanakan');
            $table->integer('jumlah_peserta')->nullable();
            $table->string('penanggung_jawab', '100')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten');
    }
};
