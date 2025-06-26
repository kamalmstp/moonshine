<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArsipSuratKeluar extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'no_surat',
        'tgl_surat',
        'tujuan',
        'perihal',
        'file_surat',
        'qr_code',
        'keterangan',
    ];
}
