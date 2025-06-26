<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Crud\IndexPage; // <-- Tetap gunakan ini
use MoonShine\Contracts\UI\ComponentContract; // <-- Tetap gunakan ini
use MoonShine\Contracts\UI\FieldContract; // <-- Tetap gunakan ini
use MoonShine\Filters\DateRangeFilter; // <-- Tambahkan ini untuk filter tanggal
use MoonShine\Filters\TextFilter;     // <-- Tambahkan ini untuk filter teks
use MoonShine\Fields\Text;             // <-- Tambahkan ini untuk kolom teks di tabel
use MoonShine\Fields\Date;             // <-- Tambahkan ini untuk kolom tanggal di tabel
use App\Models\ArsipSuratKeluar;       // <-- PASTIKAN ANDA MENGIMPOR MODEL INI
use MoonShine\Actions\ExportAction;     // <-- Tambahkan ini untuk tombol ekspor
use Throwable;

/**
 * @extends IndexPage<ModelResource> // Tipe ini mungkin tidak perlu diubah
 */
class LaporanSuratKeluarPage extends IndexPage
{
    protected string $title = 'Laporan Surat Keluar';

    // *** PENTING: Anda harus mendefinisikan model yang akan digunakan halaman ini ***
    protected string $model = ArsipSuratKeluar::class;

    /**
     * Mendefinisikan filter-filter yang akan muncul di halaman laporan.
     * Filter akan secara otomatis diterapkan ke query() di bawah.
     * @return array
     */
    public function filters(): array
    {
        return [
            DateRangeFilter::make('Tanggal Surat', 'tgl_surat') // 'tgl_surat' adalah nama kolom di database Anda
                ->placeholder('Pilih Rentang Tanggal'),
            
            TextFilter::make('Tujuan', 'tujuan') // 'tujuan' adalah nama kolom di database Anda
                ->placeholder('Filter Tujuan'),
            
            TextFilter::make('Perihal', 'perihal') // 'perihal' adalah nama kolom di database Anda
                ->placeholder('Filter Perihal'),
        ];
    }

    /**
     * Mendefinisikan query untuk mendapatkan data laporan.
     * Query ini akan otomatis mendapatkan filter dari metode filters().
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return ArsipSuratKeluar::query()->orderBy('tgl_surat', 'desc');
    }

    /**
     * Mendefinisikan kolom-kolom yang akan ditampilkan di tabel laporan.
     * Ini sama dengan bagaimana Anda mendefinisikan fields di indexFields() resource.
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Text::make('ID', 'id_surat_keluar'),
            Text::make('No. Surat', 'no_surat'),
            Date::make('Tgl. Surat', 'tgl_surat')->format('d M Y')->sortable(),
            Text::make('Tujuan', 'tujuan'),
            Text::make('Perihal', 'perihal'),
            Text::make('Keterangan', 'keterangan'),
            // Anda bisa menambahkan kolom lain sesuai kebutuhan laporan
        ];
    }

    /**
     * Mendefinisikan tombol-tombol yang akan muncul di halaman laporan.
     * Termasuk tombol ekspor.
     * @return list<ComponentContract>
     */
    protected function buttons(): array
    {
        return [
            ExportAction::make('Ekspor ke Excel')
                ->inModal(false) // Untuk langsung download, bukan di modal
                ->disk('public') // Disk tempat ekspor sementara disimpan
                ->filename('laporan_surat_keluar_' . now()->format('Ymd_His')) // Nama file dinamis
                ->xlsx() // Format Excel (bisa juga ->csv() untuk CSV)
                ->when(fn() => $this->query()->count() > 0), // Hanya tampilkan jika ada data
        ];
    }

    // Metode topLayer(), mainLayer(), dan bottomLayer() dari scaffold Anda
    // tidak perlu diubah atau diisi untuk fungsionalitas dasar laporan ini,
    // karena filters(), query(), fields(), dan buttons() sudah diurus otomatis oleh IndexPage.
    // Anda bisa menggunakannya jika ingin menempatkan komponen UI tambahan di lokasi spesifik.
    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}