<?php

namespace PanteraFox\Providers;

use PanteraFox\Services\AppService;
use PanteraFox\Services\AvatarManager;
use PanteraFox\Services\CountryManager;
use PanteraFox\Services\CoverManager;
use PanteraFox\Services\IpManager;
use PanteraFox\Services\PhotoManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use PanteraFox\Services\VideoManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $appService = new AppService();
        View::share([
            'countryManager' => app()->make(CountryManager::class),
        ]);
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('PanteraFox\Services\AppService', function ($app) {
            return new AppService();
        });
        
        $this->app->bind('PanteraFox\Services\PhotoManager', function () {
            return new PhotoManager();
        });

        $this->app->bind('PanteraFox\Services\VideoManager', function () {
            return new VideoManager();
        });

        $this->app->bind('PanteraFox\Services\CountryManager', function () {
            return new CountryManager();
        });

        $this->app->bind('PanteraFox\Services\AvatarManager', function () {
            return new AvatarManager();
        });

        $this->app->bind('PanteraFox\Services\CoverManager', function () {
            return new CoverManager();
        });

        $this->app->bind('PanteraFox\Services\IpManager', function () {
            return new IpManager();
        });
    }
}
