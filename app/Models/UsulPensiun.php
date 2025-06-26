<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsulPensiun extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'alasan_pensiun',
        'tgl_pengajuan',
        'dokumen_pendukung'
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}
