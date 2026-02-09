<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['layanan', 'tema','transporasi','etika', 'keunggulan','informasi']);
            $table->string('gambar')->nullable();
            $table->unsignedBigInteger('icon')->nullable();
            $table->foreign('icon')
                ->references('id_kategori')
                ->on('kategori')
                ->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
