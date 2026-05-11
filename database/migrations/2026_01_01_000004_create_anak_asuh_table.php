<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anak_asuh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panti_asuhan_id')->constrained('panti_asuhan')->onDelete('cascade');
            $table->string('nama', '50');
            $table->string('nik', 16)->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', '100')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama', '50')->nullable();
            $table->text('alamat_asal')->nullable();
            $table->string('asal_daerah', '100')->nullable();
            $table->enum('status_yatim', ['yatim', 'piatu', 'yatim_piatu', 'dhuafa', 'terlantar'])->nullable();
            $table->enum('jenis_tinggal', ['dalam', 'luar']);

            // Data orang tua / wali
            $table->string('nama_ayah', '50')->nullable();
            $table->string('nama_ibu', '50')->nullable();
            $table->string('pekerjaan_ortu', '100')->nullable();
            $table->string('no_telp_wali', '20')->nullable();

            // Data pendidikan
            $table->string('jenjang_pendidikan')->nullable();
            $table->string('nama_sekolah', '100')->nullable();
            $table->string('kelas', '50')->nullable();

            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->text('alasan_keluar')->nullable();

            $table->string('foto', '255')->nullable();  // foto profil anak asuh
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anak_asuh');
    }
};
