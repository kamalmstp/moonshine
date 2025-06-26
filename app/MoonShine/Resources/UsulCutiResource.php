<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\UsulCuti;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Select, Date, Textarea};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

use MoonShine\Actions\FiltersAction;


/**
 * @extends ModelResource<UsulCuti>
 */
class UsulCutiResource extends ModelResource
{
    protected string $model = UsulCuti::class;

    protected string $title = 'Usul Cuti';

    protected array $search = ['jenis_cuti', 'alasan_cuti', 'status'];
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            Text::make('Pegawai', 'pegawai.nama_pegawai')
                ->sortable(),
            Text::make('Jenis Cuti', 'jenis_cuti')->sortable(),
            Date::make('Tanggal Mulai', 'tgl_mulai')->format('d M Y')->sortable(),
            Date::make('Tanggal Selesai', 'tgl_selesai')->format('d M Y')->sortable(),
            Text::make('Alasan Cuti', 'alasan_cuti')->nullable(),
            Text::make('Status', 'status')->sortable(),
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
                    ->hint('Pilih pegawai yang mengajukan cuti.')
                    ->required(),
                
                Text::make('Jenis Cuti', 'jenis_cuti')
                    ->required()
                    ->hint('Contoh: Cuti Tahunan, Cuti Sakit, Cuti Bersalin.'),
                
                Date::make('Tanggal Mulai', 'tgl_mulai')
                    ->format('Y-m-d') // Format penyimpanan di database
                    ->required(),
                
                Date::make('Tanggal Selesai', 'tgl_selesai')
                    ->format('Y-m-d') // Format penyimpanan di database
                    ->required(),
                
                Textarea::make('Alasan Cuti', 'alasan_cuti')
                    ->required()
                    ->hint('Jelaskan alasan pengajuan cuti.'),
                
                Select::make('Status', 'status')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->default('Menunggu')
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
            ID::make(),
            Text::make('Pegawai', 'pegawai.nama_pegawai'),
            Text::make('Jenis Cuti', 'jenis_cuti'),
            Date::make('Tanggal Mulai', 'tgl_mulai')->format('d M Y'),
            Date::make('Tanggal Selesai', 'tgl_selesai')->format('d M Y'),
            Text::make('Alasan Cuti', 'alasan_cuti'),
            Text::make('Status', 'status'),
        ];
    }

    /**
     * @param UsulCuti $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'jenis_cuti' => ['required', 'string', 'max:255'],
            'tgl_mulai' => ['required', 'date'],
            'tgl_selesai' => ['required', 'date', 'after_or_equal:tgl_mulai'],
            'alasan_cuti' => ['required', 'string'],
            'status' => ['required', 'in:Disetujui,Ditolak,Menunggu'],
        ];
    }

    public function search(): array
    {
        return ['id', 'jenis_cuti', 'status'];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
