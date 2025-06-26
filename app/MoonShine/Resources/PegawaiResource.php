<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pegawai; // Pastikan model Pegawai di-import

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;   // Menggunakan MoonShine\UI\Fields\ID
use MoonShine\UI\Fields\Text; // Menggunakan MoonShine\UI\Fields\Text
use MoonShine\UI\Fields\Date; // Menggunakan MoonShine\UI\Fields\Date
use MoonShine\UI\Fields\Select; // Menggunakan MoonShine\UI\Fields\Date
use MoonShine\Actions\FiltersAction; // Import FiltersAction jika ingin menggunakan filter

/**
 * @extends ModelResource<Pegawai>
 */
class PegawaiResource extends ModelResource
{
    protected string $model = Pegawai::class;

    protected string $title = 'Pegawai';

    protected string $column = 'nama_pegawai';

    protected array $search = ['id', 'nama_pegawai', 'nip', 'jabatan', 'golongan', 'status'];

    /**
     * Define fields for the index page (list view).
     * @return list<\MoonShine\Contracts\Fields\FieldContract>
     */
    protected function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Nama Pegawai', 'nama_pegawai')->sortable(),
            Text::make('NIP', 'nip')->sortable(),
            Text::make('Jabatan', 'jabatan')->sortable(),
            Text::make('Golongan', 'golongan')->sortable(),
            Date::make('Tanggal Masuk', 'tgl_masuk')->format('d M Y')->sortable(),
            Text::make('Status', 'status')->sortable(),
        ];
    }

    /**
     * Define fields for the form (create/edit page).
     * @return list<\MoonShine\Contracts\Fields\FieldContract>
     */
    protected function formFields(): array
    {
        return [
            // ID tidak perlu ditampilkan di form karena otomatis oleh database atau diambil dari URL saat edit

            Text::make('Nama Pegawai', 'nama_pegawai')
                ->required() // Field ini wajib diisi
                ->hint('Masukkan nama lengkap pegawai.'), // Contoh hint
            Text::make('NIP', 'nip')
                ->required()
                ->hint('Nomor Induk Pegawai, harus unik.'),
            Text::make('Jabatan', 'jabatan')
                ->required()
                ->hint('Contoh: Staff IT, Manajer Pemasaran.'),
            Text::make('Golongan', 'golongan')
                ->nullable() // Field ini boleh kosong
                ->hint('Contoh: III/a, IV/b.'),
            Date::make('Tanggal Masuk', 'tgl_masuk')
                ->format('Y-m-d') // Format penyimpanan di database
                // ->withCalendar() // Dihapus karena method ini tidak ada
                ->required(),
            Select::make('Status', 'status')
                ->options([
                    'PNS' => 'PNS',
                    'PPPK' => 'PPPK',
                    'Honorer Provinsi' => 'Honorer Provinsi',
                    'Honorer Sekolah' => 'Honorer Sekolah',
                ])
                ->required(), // Jika di migrasi tidak ada ->nullable(), maka di sini required()
        ];
    }

    /**
     * Define fields for the detail page (single record view).
     * @return list<\MoonShine\Contracts\Fields\FieldContract>
     */
    protected function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Nama Pegawai', 'nama_pegawai'),
            Text::make('NIP', 'nip'),
            Text::make('Jabatan', 'jabatan'),
            Text::make('Golongan', 'golongan'),
            Date::make('Tanggal Masuk', 'tgl_masuk')->format('d M Y'),
            Text::make('Status', 'status'),
        ];
    }

    /**
     * Define validation rules for the model.
     * @param Pegawai $item
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        // Aturan validasi Laravel
        return [
            'nama_pegawai' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:255', 'unique:pegawais,nip,' . ($item->id ?? 'NULL')],
            'jabatan' => ['required', 'string', 'max:255'],
            'golongan' => ['nullable', 'string', 'max:255'], // Golongan bisa null
            'tgl_masuk' => ['required', 'date'],
            'status' => ['required', 'in:PNS,PPPK,Honorer Provinsi,Honorer Sekolah'],
        ];
    }

    /**
     * Define the columns that can be used for search.
     * @return array<string>
     */
    public function search(): array
    {
        return ['id', 'nama_pegawai', 'nip', 'jabatan', 'golongan', 'status'];
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
