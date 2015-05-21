<?php

namespace Ipalaus\File\Providers;

use Illuminate\Support\ServiceProvider;
use Ipalaus\File\Repository\IlluminateRepository;
use Ipalaus\File\Storage\Manager as StorageManager;

class LumenServiceProvider extends LaravelServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->app->configure('file');
    }
}
