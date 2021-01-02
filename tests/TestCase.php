<?php

namespace AusbildungMS\Shorty\Tests;

use AusbildungMS\Shorty\ShortyServiceProvider;
use CreateShortyTables;
use CreateShortyTestTables;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use \Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{

    public function setUp(): void
    {
        parent::setUp();

        Route::shorty();

        Factory::guessFactoryNamesUsing(
            function (string $modelName) {
                return 'AusbildungMS\\Shorty\\Database\\Factories\\' . class_basename($modelName) . 'Factory';
            }
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ShortyServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('shorty.excluded_domains', false);
        config()->set('shorty.root_route', '/r/{link:short}');
        config()->set('shorty.root_domain', 'shorty-app.com');

        include_once __DIR__.'/database/migrations/create_shorty_test_tables.php.stub';
        (new CreateShortyTestTables())->up();

        include_once __DIR__.'/../database/migrations/create_shorty_tables.php.stub';
        (new CreateShortyTables())->up();
    }
}