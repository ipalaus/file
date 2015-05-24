<?php

namespace Ipalaus\File\Providers;

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
