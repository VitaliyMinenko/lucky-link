<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SerializerInterface::class, function () {
            return SerializerBuilder::create()->build();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
