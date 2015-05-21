<?php

namespace Ipalaus\File\Support;

use Illuminate\Support\Facades\Facade;

class FileFacade extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'file';
    }
}
