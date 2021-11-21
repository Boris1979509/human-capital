<?php

namespace App\Providers;

use Dotenv\Dotenv;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\Config as SocialiteConfig;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap(Config::get('morph_map'));
        Dotenv::createImmutable($this->app->basePath())->load();
    }
}
