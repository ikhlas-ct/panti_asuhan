<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('konten', function (Blueprint $table) {
            // 1. Hapus foreign key dulu
            $table->dropForeign(['id_kategori']);

            // 2. Ubah kolom jadi nullable
            $table->unsignedBigInteger('id_kategori')->nullable()->change();

            // 3. Tambahkan foreign key lagi
            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori')
                ->nullOnDelete(); // lebih aman daripada cascade
        });
    }

    public function down(): void
    {
        Schema::table('konten', function (Blueprint $table) {
            $table->dropForeign(['id_kategori']);

            $table->unsignedBigInteger('id_kategori')->nullable(false)->change();

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori')
                ->onDelete('cascade');
        });
    }
};
