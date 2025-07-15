<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/laporan-pegawai/cetak-pdf', function () {
    $pegawais = Pegawai::all();

    $pdf = Pdf::loadView('pdf.laporan-pegawai', compact('pegawais'));

    return $pdf->download('laporan-pegawai.pdf');
})->name('laporan.pegawai.pdf');