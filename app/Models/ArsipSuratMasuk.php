<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArsipSuratMasuk extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'no_surat',
        'tgl_terima',
        'pengirim',
        'perihal',
        'lampiran',
        'file_surat',
        'qr_code',
        'keterangan',
    ];
}
