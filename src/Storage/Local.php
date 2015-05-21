<?php

namespace Ipalaus\File\Storage;

use Illuminate\Contracts\Filesystem\FileNotFoundException as IlluminateFileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Ipalaus\File\Contracts\FileNotFoundException;
use Ipalaus\File\Contracts\InvalidFileHandleException;
use Ipalaus\File\Contracts\Storage;

class Local extends Engine implements Storage
{
    public $engineName = 'local';
    public $engineFormat = 'raw';

    /**
     * Filesystem implementation.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The path where files should be stored.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new local storage instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @param string                            $path
     */
    public function __construct(Filesystem $filesystem, $path)
    {
        $this->filesystem = $filesystem;
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function write($data, array $options)
    {
        $name = $this->generateName();

        $parent = $this->path . '/' . dirname($name);

        // Make sure the parent directory exists and create it is missing
        if ( ! $this->filesystem->isDirectory($parent)) {
            $this->filesystem->makeDirectory($parent, 0755, true);
        }

        // Write the file to the disk
        $this->filesystem->put("{$this->path}/{$name}", $data);

        return $name;
    }


    /**
     * {@inheritDoc}
     */
    public function read($handle)
    {
        $path = $this->getLocalPath($handle);

        try {
            return $this->filesystem->get($path);
        } catch (IlluminateFileNotFoundException $e) {
            throw new FileNotFoundException;
        }
    }

    /**
     * Generate a random, unique file path like "ab/29/1f918a9ac39201ff". We
     * put a couple of subdirectories up front to avoid a situation where we
     * have one directory with a zillion files in it, since this is generally
     * bad news.
     *
     * @return string
     */
    protected function generateName()
    {
        do {
            $name = md5(mt_rand());
            $name = preg_replace('/^(..)(..)(.*)$/', '\\1/\\2/\\3', $name);

            if ( ! $this->filesystem->exists("{$this->path}/{$name}")) {
                return $name;
            }
        } while (true);
    }

    /**
     * Convert a handle to an absolute path in the local disk.
     *
     * @param string $handle
     *
     * @return string
     * @throws \Ipalaus\File\Contracts\InvalidFileHandleException
     */
    protected function getLocalPath($handle)
    {
        if ( ! preg_match('@^[a-f0-9]{2}/[a-f0-9]{2}/[a-f0-9]{28}\z@', $handle)) {
            throw new InvalidFileHandleException("Local disk filesystem handle '{$handle}' is malformed!");
        }

        return "{$this->path}/{$handle}";
    }
}
