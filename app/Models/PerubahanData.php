<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerubahanData extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'jenis_perubahan',
        'data_baru',
        'data_lama',
        'status',
    ];

    /**
     * Get the pegawai that owns the PerubahanData.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}