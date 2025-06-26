<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerjalananDinas extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'pegawai_id',
        'tujuan_dinas',
        'tgl_berangkat',
        'tgl_kembali',
    ];

    protected $casts = [
        'tgl_berangkat' => 'date',
        'tgl_kembali' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}
