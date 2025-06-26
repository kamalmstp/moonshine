<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permohonan;
use App\Models\Pegawai;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Select, Textarea};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

use MoonShine\Actions\FiltersAction;

/**
 * @extends ModelResource<Permohonan>
 */
class PermohonanResource extends ModelResource
{
    protected string $model = Permohonan::class;

    protected string $title = 'Permohonan';
    protected array $search = ['id', 'jenis_permohonan', 'data_baru', 'keterangan'];
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            Text::make('Pegawai', 'pegawai.nama_pegawai'),
            Text::make('Jenis Permohonan', 'jenis_permohonan'),
            Text::make('Data Baru', 'data_baru'),
            Text::make('Keterangan', 'keterangan')->nullable(),
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
                    ->hint('Pilih pegawai yang mengajukan permohonan.')
                    ->nullable(),
                Text::make('Jenis Permohonan', 'jenis_permohonan')
                    ->required()
                    ->hint('Contoh: Cuti, Mutasi, Kenaikan Pangkat.'),
                Textarea::make('Data Baru', 'data_baru')
                    ->nullable()
                    ->hint('Detail data baru yang diajukan, jika ada.'),
                Textarea::make('Keterangan', 'keterangan')
                    ->nullable()
                    ->hint('Catatan atau alasan tambahan.'),
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
            Text::make('Jenis Permohonan', 'jenis_permohonan'),
            Text::make('Data Baru', 'data_baru'),
            Text::make('Keterangan', 'keterangan'),
        ];
    }

    /**
     * @param Permohonan $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'jenis_permohonan' => ['required', 'string', 'max:255'],
            'data_baru' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function search(): array
    {
        return ['id', 'jenis_permohonan', 'data_baru', 'keterangan'];
    }

    /**
     * Define the actions (e.g., filters) for the resource.
     * @return list<\MoonShine\Actions\Action>
     */
    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
