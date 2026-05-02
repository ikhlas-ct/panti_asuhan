<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnakAsuh extends Model
{
    protected $table = 'anak_asuh';
    protected $primaryKey = 'id';

    protected $fillable = [
        'panti_asuhan_id',
        'nama',
        'nik',
        'no_kk',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat_asal',
        'asal_daerah',
        'status_yatim',
        'jenis_tinggal',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ortu',
        'no_telp_wali',
        'jenjang_pendidikan',
        'nama_sekolah',
        'kelas',
        'tanggal_masuk',
        'tanggal_keluar',
        'alasan_keluar',
        'foto',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir'      => 'date',
        'tanggal_masuk'      => 'date',
        'tanggal_keluar'     => 'date',
        'jenis_kelamin'      => 'string',
        'status_yatim'       => 'string',
        'jenis_tinggal'      => 'string',
        'jenjang_pendidikan' => 'string',
        'status'             => 'string',
    ];

    public function pantiAsuhan(): BelongsTo
    {
        return $this->belongsTo(PantiAsuhan::class, 'panti_asuhan_id');
    }

    public function getUsiaAttribute(): ?int
    {
        return $this->tanggal_lahir?->age;
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeDalam($query)
    {
        return $query->where('jenis_tinggal', 'dalam');
    }

    public function scopeLuar($query)
    {
        return $query->where('jenis_tinggal', 'luar');
    }
}
