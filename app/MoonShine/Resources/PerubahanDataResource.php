<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\PerubahanData;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Select, Textarea};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

use MoonShine\Actions\FiltersAction;

/**
 * @extends ModelResource<PerubahanData>
 */
class PerubahanDataResource extends ModelResource
{
    protected string $model = PerubahanData::class;

    protected string $title = 'Perubahan Data';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            Text::make('Pegawai', 'pegawai.nama_pegawai'),
            Text::make('Jenis Perubahan', 'jenis_perubahan'),
            Text::make('Data Baru', 'data_baru')->nullable(),
            Text::make('Data Lama', 'data_lama')->nullable(),
            Text::make('Status', 'status')->sortable(),
        ];
    }

    /**
     * Tentukan field untuk form tambah/edit (form page).
     *
     * @return list<\MoonShine\Contracts\Fields\FieldContract>
     */
    protected function formFields(): array
    {
        return [
            Box::make([
                // Field untuk memilih pegawai (relasi BelongsTo)
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class)
                    ->searchable() // Aktifkan pencarian di dropdown
                    ->hint('Pilih pegawai yang mengajukan perubahan data.')
                    ->required(), // Sesuaikan dengan aturan validasi di rules()
                
                Text::make('Jenis Perubahan', 'jenis_perubahan')
                    ->required()
                    ->hint('Contoh: Nama, Jabatan, Golongan.'),
                
                Textarea::make('Data Baru', 'data_baru')
                    ->nullable()
                    ->hint('Isi dengan data terbaru yang diajukan.'),
                
                Textarea::make('Data Lama', 'data_lama')
                    ->nullable()
                    ->hint('Isi dengan data lama yang akan diubah.'),
                
                // Field untuk memilih status (enum)
                Select::make('Status', 'status')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->default('Menunggu') // Nilai default saat membuat baru
                    ->required(),
            ])
        ];
    }

    /**
     * Tentukan field untuk tampilan detail (detail page).
     *
     * @return list<\MoonShine\Contracts\Fields\FieldContract>
     */
    protected function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Pegawai', 'pegawai.nama_pegawai'),
            Text::make('Jenis Perubahan', 'jenis_perubahan'),
            Text::make('Data Baru', 'data_baru'),
            Text::make('Data Lama', 'data_lama'),
            Text::make('Status', 'status'),
        ];
    }

    /**
     * @param PerubahanData $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'jenis_perubahan' => ['required', 'string', 'max:100'],
            'data_baru' => ['nullable', 'string'],
            'data_lama' => ['nullable', 'string'],
            'status' => ['required', 'in:Disetujui,Ditolak,Menunggu'],
        ];
    }

    public function search(): array
    {
        return ['id', 'jenis_perubahan', 'status'];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
