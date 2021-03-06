<?php

namespace Ipalaus\File\Providers;

use Illuminate\Support\ServiceProvider;
use Ipalaus\File\File;
use Ipalaus\File\Repositories\IlluminateFileRepository;
use Ipalaus\File\Repositories\IlluminateTransformationRepository;
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
            realpath(__DIR__ . '/../config/config.php') => $this->app->configPath() . '/file.php'
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
        $this->registerRepositories();
        $this->registerFile();
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
     * Register the repositories.
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app['file.repository'] = $this->app->share(function ($app) {
            return new IlluminateFileRepository($app['config']->get('file.model.file'));
        });

        $this->app['file.repository.transformation'] = $this->app->share(function ($app) {
            return new IlluminateTransformationRepository($app['config']->get('file.model.transformation'));
        });
    }

    /**
     * Register the File clasas and file alias.
     *
     * @return void
     */
    protected function registerFile()
    {
        $this->app->bind('Ipalaus\File\File', function ($app) {
            $storageEngine = $app['file.storage.store'];
            $fileRepository = $app['file.repository'];
            $transformationRepository = $app['file.repository.transformation'];
            $fileBag = $app['request']->files;
            $transformers = $app['config']->get('file.transformers');

            return new File($storageEngine, $fileRepository, $transformationRepository, $fileBag, $app, $transformers);
        });

        $this->app->alias('Ipalaus\File\File', 'file');
    }
}
