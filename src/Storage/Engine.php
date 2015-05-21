<?php

namespace Ipalaus\File\Storage;

abstract class Engine
{
    /**
     * Get the engine name.
     *
     * @return string
     */
    public function getEngineName()
    {
        return $this->engineName;
    }

    /**
     * Get the engine format.
     *
     * @return string
     */
    public function getEngineFormat()
    {
        return $this->engineFormat;
    }
}
