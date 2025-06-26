<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When};
use App\MoonShine\Resources\PegawaiResource;
use MoonShine\MenuManager\MenuItem;
use MoonShine\MenuManager\MenuGroup;
use App\MoonShine\Resources\PerjalananDinasResource;
use App\MoonShine\Resources\PermohonanResource;
use App\MoonShine\Resources\PerubahanDataResource;
use App\MoonShine\Resources\UsulCutiResource;
use App\MoonShine\Resources\UsulPensiunResource;
use App\MoonShine\Resources\ArsipSuratKeluarResource;
use App\MoonShine\Resources\ArsipSuratMasukResource;

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [

            MenuGroup::make('Kepegawaian', [
                MenuItem::make('Pegawai', PegawaiResource::class),
                MenuItem::make('Perjalanan Dinas', PerjalananDinasResource::class),
                MenuItem::make('Permohonan', PermohonanResource::class),
                MenuItem::make('Perubahan Data', PerubahanDataResource::class),
                MenuItem::make('Usul Cuti', UsulCutiResource::class),
                MenuItem::make('Usul Pensiun', UsulPensiunResource::class),
            ]),
            MenuGroup::make('Persuratan', [
                MenuItem::make('Arsip Surat Keluar', ArsipSuratKeluarResource::class),
                MenuItem::make('Arsip Surat Masuk', ArsipSuratMasukResource::class),
            ]),
            ...parent::menu(),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
