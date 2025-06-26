<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\PerjalananDinas;
use App\Models\Pegawai;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

use MoonShine\Actions\FiltersAction;
/**
 * @extends ModelResource<PerjalananDinas>
 */
class PerjalananDinasResource extends ModelResource
{
    protected string $model = PerjalananDinas::class;

    protected string $title = 'PerjalananDinas';

    protected array $search = ['id', 'tujuan_dinas', 'tgl_berangkat', 'tgl_kembali'];
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            Text::make('Pegawai', 'pegawai.nama_pegawai'),
            Text::make('Tujuan Dinas', 'tujuan_dinas')->sortable(),
            Date::make('Tgl Berangkat', 'tgl_berangkat')->format('d M Y')->sortable(),
            Date::make('Tgl Kembali', 'tgl_kembali')->format('d M Y')->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class)
                ->searchable()
                ->nullable()
                ->hint('Pilih pegawai yang melakukan perjalanan dinas.'),
            Text::make('Tujuan Dinas', 'tujuan_dinas')
                ->required()
                ->hint('Contoh: Rapat Koordinasi di Jakarta.'),
            Date::make('Tgl Berangkat', 'tgl_berangkat')
                ->format('Y-m-d')
                ->required(),
            Date::make('Tgl Kembali', 'tgl_kembali')
                ->format('Y-m-d')
                ->required(),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            Text::make('Pegawai', 'pegawai.nama_pegawai'),
            Text::make('Tujuan Dinas', 'tujuan_dinas'),
            Date::make('Tgl Berangkat', 'tgl_berangkat')->format('d M Y'),
            Date::make('Tgl Kembali', 'tgl_kembali')->format('d M Y'),
        ];
    }

    /**
     * @param PerjalananDinas $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawais,id'], // Pastikan pegawai_id ada di tabel pegawais
            'tujuan_dinas' => ['required', 'string', 'max:255'],
            'tgl_berangkat' => ['required', 'date'],
            'tgl_kembali' => ['required', 'date', 'after_or_equal:tgl_berangkat'], // Tgl kembali harus setelah atau sama dengan tgl berangkat
        ];
    }

    public function search(): array
    {
        return ['id', 'tujuan_dinas'];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
