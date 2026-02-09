<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_step', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
                ->constrained('service')
                ->cascadeOnDelete();

            $table->integer('step_number');
            $table->string('title');
            $table->unsignedBigInteger('icon')->nullable();
            $table->foreign('icon')
                ->references('id_kategori')
                ->on('kategori')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_step');
    }
};
