<?php

namespace Ipalaus\File\Contracts;

interface Storage
{
    public function write($data, array $params);

    /**
     * Read a handle from storage engine.
     *
     * @param string $handle
     *
     * @return string
     * @throws \Ipalaus\File\Contracts\FileNotFoundException
     * @throws \Ipalaus\File\Contracts\InvalidFileHandleException
     */
    public function read($handle);

    /**
     * Get the engine name.
     *
     * @return string
     */
    public function getEngineName();

    /**
     * Get the engine format.
     *
     * @return string
     */
    public function getEngineFormat();
}
