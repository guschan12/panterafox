<?php
/**
 * Created by PhpStorm.
 * User: Hamlet
 * Date: 28.09.2018
 * Time: 22:14
 */

namespace PanteraFox\Subscription\Resources\Providers;


use Illuminate\Support\ServiceProvider;
use PanteraFox\Subscription\Application\SubscriptionService;
use PanteraFox\Subscription\Application\UserNewsService;
use PanteraFox\Subscription\Data\UserNewsRepository;

class SubscriptionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SubscriptionService::class, function ($app) {
            return new SubscriptionService();
        });

        $this->app->bind(UserNewsService::class, function ($app) {
            $repo = $this->app->make(UserNewsRepository::class);
           return new UserNewsService(
                    $app->make(UserNewsRepository::class)
           );
        });

        $this->app->bind(UserNewsRepository::class, function ($app) {
            return new UserNewsRepository();
        });
    }
}