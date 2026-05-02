<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panti_asuhan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_panti','50');
            $table->string('alamat','100');
            $table->string('kelurahan','50')->nullable();
            $table->string('kecamatan','50')->nullable();
            $table->string('no_telp','20')->nullable();
            $table->string('nama_kontak','50')->nullable();
            $table->string('email','100')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panti_asuhan');
    }
};
