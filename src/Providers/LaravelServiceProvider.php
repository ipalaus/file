<?php

namespace Ipalaus\File\Providers;

use Illuminate\Support\ServiceProvider;
use Ipalaus\File\File;
use Ipalaus\File\Repository\IlluminateRepository;
use Ipalaus\File\Storage\Manager as StorageManager;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->mergeConfigFrom(realpath(__DIR__ . '/../config/config.php'), 'file');

        $this->publishes([
            realpath(__DIR__ . '/../config/config.php') => $this->app->configPath() . '/config.php'
        ], 'config');

        $this->publishes([
            realpath(__DIR__ . '/../migrations') => $this->app->databasePath() . '/migrations'
        ], 'migrations');
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->registerStorage();
        $this->registerRepository();

        $this->app->bind('Ipalaus\File\File', function ($app) {
            return new File($app['file.storage.store'], $app['file.repository'], $app['request']->files);
        });

        $this->app->alias('Ipalaus\File\File', 'file');
    }

    /**
     * Register the storage manager.
     *
     * @return void
     */
    protected function registerStorage()
    {
        $this->app->singleton('file.storage', function ($app) {
            return new StorageManager($app);
        });

        $this->app->singleton('file.storage.store', function ($app) {
            return $app['file.storage']->driver();
        });
    }

    /**
     * Register the repository.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app['file.repository'] = $this->app->share(function ($app) {
            return new IlluminateRepository($app['config']->get('file.model'));
        });
    }
}
