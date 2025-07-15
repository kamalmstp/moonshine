<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\Layout\Layout;
use MoonShine\UI\Components\Raw;
use MoonShine\UI\Fields\{ID, Text, Select, Date, File, Textarea, Image};
use MoonShine\UI\Components\ActionButton;
use App\Models\Pegawai;


class LaporanPegawai extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Laporan Pegawai';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
	{
		return [
            Layout::make([
                
                TableBuilder::make()
                    ->fields([
                        Text::make('Nama Pegawai', 'nama_pegawai'),
                        Text::make('NIP', 'nip'),
                        Text::make('Jabatan', 'jabatan'),
                        Text::make('Golongan', 'golongan'),
                        Date::make('Tgl Masuk', 'tgl_masuk')->format('d M Y'),
                        Text::make('Status', 'status')
                    ])
                    ->items(Pegawai::all())
                    ->buttons([
                        ActionButton::make('Download PDF', route('laporan.pegawai.pdf'))
                    ])
            ])
        ];
	}
}
