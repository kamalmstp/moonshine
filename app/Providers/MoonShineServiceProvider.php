<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\PegawaiResource;
use App\MoonShine\Resources\PerjalananDinasResource;
use App\MoonShine\Resources\PermohonanResource;
use App\MoonShine\Resources\PerubahanDataResource;
use App\MoonShine\Resources\UsulCutiResource;
use App\MoonShine\Resources\UsulPensiunResource;
use App\MoonShine\Resources\ArsipSuratKeluarResource;
use App\MoonShine\Resources\ArsipSuratMasukResource;
use App\MoonShine\Pages\LaporanPegawai;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        // $config->authEnable();

        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                PegawaiResource::class,
                PerjalananDinasResource::class,
                PermohonanResource::class,
                PerubahanDataResource::class,
                UsulCutiResource::class,
                UsulPensiunResource::class,
                ArsipSuratKeluarResource::class,
                ArsipSuratMasukResource::class,
            ])
            ->pages([
                ...$config->getPages(),
                LaporanPegawai::class,
            ])
        ;
    }
}
