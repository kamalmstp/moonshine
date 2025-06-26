<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pegawai',
        'nip',
        'jabatan',
        'golongan',
        'tgl_masuk',
        'status',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
    ];

    public function perjalananDinas(): HasMany
    {
        return $this->hasMany(PerjalananDinas::class);
    }

    public function permohonan(): HasMany
    {
        return $this->hasMany(Permohonan::class);
    }

    public function perubahanData(): HasMany
    {
        return $this->hasMany(PerubahanData::class);
    }

    public function usulCuti(): HasMany
    {
        return $this->hasMany(UsulCuti::class);
    }
}