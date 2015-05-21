<?php

namespace Ipalaus\File\Storage;

use Illuminate\Support\Manager as IlluminateManager;

class Manager extends IlluminateManager
{
    /**
     * Create the local driver.
     *
     * @return \Ipalaus\File\Storage\Local
     */
    protected function createLocalDriver()
    {
        $path = $this->app['config']['file.storage.drivers.local.root'];

        return new Local($this->app['files'], $path);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['file.storage.default'];
    }

    /**
     * Set the default file storage driver name.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['file.storage.default'] = $name;
    }
}
