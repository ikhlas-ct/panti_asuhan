<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoPanti extends Model
{
    protected $table = 'foto_panti';
    protected $primaryKey = 'id';

    protected $fillable = [
        'panti_asuhan_id',
        'foto',
        'keterangan',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function pantiAsuhan(): BelongsTo
    {
        return $this->belongsTo(PantiAsuhan::class, 'panti_asuhan_id');
    }
}
