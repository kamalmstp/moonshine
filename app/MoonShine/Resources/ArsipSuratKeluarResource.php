<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ArsipSuratKeluar;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Select, Date, File, Textarea, Image};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

use MoonShine\Actions\FiltersAction;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * @extends ModelResource<ArsipSuratKeluar>
 */
class ArsipSuratKeluarResource extends ModelResource
{
    protected string $model = ArsipSuratKeluar::class;

    protected string $title = 'Arsip Surat Keluar';

    protected array $search = ['no_surat', 'tujuan', 'perihal'];
    

    public function afterSave(mixed $item, FieldsContract $fields): mixed
    {
        //static $isUpdatingQrCode = false;

        if ($item->file_surat) { 

            $fileName = basename($item->file_surat); 

            $qrCodeContentUrl = 'http://localhost:8000/surat_keluar/'.$fileName;

            $qrCodeDirPath = 'surat_keluar/qrcode';
            
            $qrCodeFileName = 'qrcode_' . $item->id . '.svg'; 
            
            $qrCodeSvgData = QrCode::size(200)->generate($qrCodeContentUrl);

            Storage::disk('public')->put($qrCodeDirPath . '/' . $qrCodeFileName, $qrCodeSvgData);

            $item->update(['qr_code' => $qrCodeDirPath . '/' . $qrCodeFileName]);

            $item->refresh();
        }
        return parent::afterSave($item, $fields);
    }

    protected function indexFields(): iterable
    {
        return [
            Text::make('No. Surat', 'no_surat')->sortable(),
            Date::make('Tgl. Surat', 'tgl_surat')->format('d M Y'),
            Text::make('Tujuan', 'tujuan')->sortable(),
            Text::make('Perihal', 'perihal'),
            File::make('File Surat', 'file_surat') 
                ->disk('public') // Pastikan disk sesuai dengan yang digunakan saat upload
                ->dir('surat_keluar'),
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_keluar/qrcode')
                ->removable(false),
            Text::make('Keterangan', 'keterangan'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('No. Surat', 'no_surat')
                    ->required()
                    ->hint('Nomor unik surat keluar. Contoh: SK/2025/001.'),
                
                Date::make('Tanggal Surat', 'tgl_surat')
                    ->format('Y-m-d')
                    ->required(),
                
                Text::make('Tujuan', 'tujuan')
                    ->required()
                    ->hint('Pihak/lembaga tujuan surat.'),
                
                Textarea::make('Perihal', 'perihal')
                    ->required()
                    ->hint('Isi ringkas surat.'),
                
                File::make('File Surat', 'file_surat') // Field untuk upload file
                    ->disk('public') // Disk yang digunakan (biasanya 'public' untuk file yang bisa diakses web)
                    ->dir('surat_keluar') // Sub-direktori dalam 'public' storage
                    ->nullable()
                    ->removable() // Bisa dihapus
                    ->hint('Unggah file surat (PDF, Word, dll).'),
                
                Textarea::make('Keterangan', 'keterangan')
                    ->nullable()
                    ->hint('Keterangan atau catatan tambahan mengenai surat.'),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            Text::make('No. Surat', 'no_surat'),
            Date::make('Tgl. Surat', 'tgl_surat')->format('d M Y'),
            Text::make('Tujuan', 'tujuan'),
            Text::make('Perihal', 'perihal'),
            Text::make('File Surat', 'file_surat'),
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_keluar/qrcode')
                ->removable(false),
            Text::make('Keterangan', 'keterangan'),
        ];
    }

    /**
     * @param ArsipSuratKeluar $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'no_surat' => ['required', 'string', 'max:50'],
            'tgl_surat' => ['required', 'date'],
            'tujuan' => ['required', 'string', 'max:100'],
            'perihal' => ['required', 'string'],
            'file_surat' => ['nullable', 'file', 'max:10240'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function search(): array
    {
        return ['no_surat', 'tujuan', 'perihal'];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
