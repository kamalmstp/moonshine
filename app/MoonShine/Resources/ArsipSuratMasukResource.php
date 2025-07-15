<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ArsipSuratMasuk;
use Illuminate\Support\Str;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Select, Date, File, Textarea, Image};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use MoonShine\Actions\FiltersAction;
use Illuminate\Support\Facades\Storage;
/**
 * @extends ModelResource<ArsipSuratMasuk>
 */
class ArsipSuratMasukResource extends ModelResource
{
    protected string $model = ArsipSuratMasuk::class;
    protected string $title = 'Arsip Surat Masuk';
    protected array $search = ['no_surat', 'penerima', 'lampiran'];
    
    public function afterSave(mixed $item, FieldsContract $fields): mixed
    {
        if ($item->file_surat) { 
            $fileName = basename($item->file_surat); 

            $qrCodeContentUrl = 'http://localhost:8000/surat_masuk/'.$fileName;

            $qrCodeDirPath = 'surat_masuk/qrcode';
            
            $qrCodeFileName = 'qrcode_' . $item->id . '.svg'; 
            
            $qrCodeSvgData = QrCode::size(200)->generate($qrCodeContentUrl);

            Storage::disk('public')->put($qrCodeDirPath . '/' . $qrCodeFileName, $qrCodeSvgData);

            $item->update(['qr_code' => $qrCodeDirPath . '/' . $qrCodeFileName]);

            $item->refresh();
        }
        return parent::afterSave($item, $fields);
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            Text::make('No. Surat', 'no_surat')->sortable(),
            Date::make('Tgl. Terima', 'tgl_terima')->format('d M Y'),
            Text::make('Pengirim', 'pengirim')->sortable(),
            Text::make('Perihal', 'perihal'),
            Text::make('Lampiran', 'lampiran'),
            Text::make('File Surat', 'file_surat'),
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_masuk/qrcode')
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
                    ->hint('Nomor unik surat masuk. Contoh: SK/2025/001.'),
                
                Date::make('Tanggal Terima', 'tgl_terima')
                    ->format('Y-m-d')
                    ->required(),
                
                Text::make('Pengirim', 'pengirim')
                    ->required()
                    ->hint('Nama Pengirim Surat.'),
                
                Textarea::make('Perihal', 'perihal')
                    ->required()
                    ->hint('Isi ringkas surat.'),

                Textarea::make('Lampiran', 'lampiran')
                    ->required(),
                
                File::make('File Surat', 'file_surat') // Field untuk upload file
                    ->disk('public')
                    ->dir('surat_masuk')
                    ->nullable()
                    ->removable()
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
            Text::make('No. Surat', 'no_surat')->sortable(),
            Date::make('Tgl. Terima', 'tgl_terima')->format('d M Y'),
            Text::make('Pengirim', 'pengirim')->sortable(),
            Text::make('Perihal', 'perihal'),
            Text::make('Lampiran', 'lampiran'),
            Text::make('File Surat', 'file_surat'),
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_masuk/qrcode')
                ->removable(false),
            Text::make('Keterangan', 'keterangan'),
        ];
    }

    /**
     * @param ArsipSuratMasuk $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
