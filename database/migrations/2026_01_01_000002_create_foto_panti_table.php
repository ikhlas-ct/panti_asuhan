<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_panti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panti_asuhan_id')->constrained('panti_asuhan')->onDelete('cascade');
            $table->string('foto', '255');
            $table->string('keterangan', '100')->nullable();
            $table->unsignedBigInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_panti');
    }
};
