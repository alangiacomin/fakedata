<?php

namespace App\Infrastructure\Providers;

use App\Areas\Main\Motto\Application\Contracts\MottoCatalogInterface;
use App\Areas\Main\Motto\Infrastructure\Catalogs\ConfigMottoCatalog;
use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaFactoryInterface;
use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaServiceInterface;
use App\Areas\Main\Persona\Application\Services\PersonaFisicaService;
use App\Areas\Main\Persona\Infrastructure\Factories\CodiceFiscaleFactory;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MottoCatalogInterface::class, ConfigMottoCatalog::class);
        $this->app->bind(PersonaFisicaFactoryInterface::class, CodiceFiscaleFactory::class);
        $this->app->bind(PersonaFisicaServiceInterface::class, PersonaFisicaService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Queue::before(function ($event) {
            if ($locale = Context::get('locale')) {
                app()->setLocale($locale);
            }
        });
    }
}
