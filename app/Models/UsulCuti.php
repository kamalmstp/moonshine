<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsulCuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'jenis_cuti',
        'tgl_mulai',
        'tgl_selesai',
        'alasan_cuti',
        'status',
    ];

    /**
     * Get the pegawai that owns the UsulCuti.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}