<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\UsulPensiun;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Select, Date, Textarea};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

use MoonShine\Actions\FiltersAction;

/**
 * @extends ModelResource<UsulPensiun>
 */
class UsulPensiunResource extends ModelResource
{
    protected string $model = UsulPensiun::class;

    protected string $title = 'UsulPensiuns';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            Text::make('Pegawai', 'pegawai.nama_pegawai')->sortable(),
            Text::make('Alasan', 'alasan_pensiun')->sortable(),
            Date::make('Tgl Pengajuan', 'tgl_pengajuan')->format('d M Y')->sortable(),
            Text::make('Dokumen Pendukung', 'dokumen_pendukung')->nullable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class)
                    ->searchable()
                    ->nullable()
                    ->hint('Pilih pegawai yang mengajukan pensiun.')
                    ->required(),
                
                Textarea::make('Alasan Pensiun', 'alasan_pensiun')
                    ->required()
                    ->hint(''),

                Date::make('Tanggal Pengajuan', 'tgl_pengajuan')
                    ->format('Y-m-d')
                    ->required(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            Text::make('Pegawai', 'pegawai.nama_pegawai'),
            Text::make('Alasan', 'alasan_pensiun'),
            Date::make('Tgl Pengajuan', 'tgl_pengajuan')->format('d M Y'),
            Text::make('Dokumen Pendukung', 'dokumen_pendukung'),
        ];
    }

    /**
     * @param UsulPensiun $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'alasan_pensiun' => ['required', 'string', 'max:255'],
            'tgl_pengajuan' => ['required', 'date'],
        ];
    }

    public function search(): array
    {
        return ['alasan_pensiun', 'tgl_pengajuan'];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
