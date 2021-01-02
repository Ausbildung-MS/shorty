<?php

namespace AusbildungMS\Shorty;

use AusbildungMS\Shorty\Http\Controller\LinkController;
use AusbildungMS\Shorty\Models\Link;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ShortyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootPublishes()
            ->registerRoutes()
            ->bootObserver();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shorty.php', 'shorty');
    }

    private function bootPublishes(): self
    {
        if (! class_exists('CreateShortyTables')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_shorty_tables.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_shorty_tables.php'),
            ], 'migrations');
        }

        return $this;
    }

    private function registerRoutes(): self
    {
        Route::pattern('shorty_excluded_domains', '^((?!' . (config('shorty.excluded_domains') ? implode('|', config('shorty.excluded_domains')) : 'asd') . ').)*$');

        Route::macro('shorty', function ($url = '') {

            if(config('shorty.root_route')) {
                Route::group(['domain' => config('shorty.root_domain')], function() {
                    Route::get(config('shorty.root_route'), ['\\' . LinkController::class, 'redirect'])->name('shorty.redirect');
                });
            }

            Route::group(['domain' => '{shorty_excluded_domains}'], function() {
                Route::get('/{link:short}', ['\\' . LinkController::class, 'redirectExtern'])->name('shorty.domain-redirect');
            });

        });

        return $this;
    }

    public function bootObserver(): self
    {
        Link::observe(LinkObserver::class);

        return $this;
    }
}