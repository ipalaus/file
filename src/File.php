<?php

namespace Ipalaus\File;

use Ipalaus\File\Contracts\File as FileContract;
use Ipalaus\File\Contracts\FileNotFoundException;
use Ipalaus\File\Contracts\Repository;
use Ipalaus\File\Contracts\Storage;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Response;

class File
{
    /**
     * Storage implementation.
     *
     * @var \Ipalaus\File\Contracts\Storage
     */
    protected $storage;

    /**
     * Repository implementation.
     *
     * @var \Ipalaus\File\Contracts\Repository
     */
    protected $repository;

    /**
     * Uploaded files ($_FILES).
     *
     * @var \Symfony\Component\HttpFoundation\FileBag
     */
    protected $fileBag;

    /**
     * Create a new file instance.
     *
     * @param \Ipalaus\File\Contracts\Storage           $storage
     * @param \Ipalaus\File\Contracts\Repository        $repository
     * @param \Symfony\Component\HttpFoundation\FileBag $fileBag
     */
    public function __construct(Storage $storage, Repository $repository, FileBag $fileBag)
    {
        $this->storage = $storage;
        $this->repository = $repository;
        $this->fileBag = $fileBag;
    }

    /**
     *
     *
     * @param string|\Symfony\Component\HttpFoundation\File\File $file
     * @param array                                              $options
     *
     * @return FileContract
     * @throws \Ipalaus\File\Contracts\FileNotFoundException
     */
    public function create($file, $options = [])
    {
        // if the file parameter is a string try to retrive it form the request
        if (is_string($file)) {
            $file = $this->retriveFromRequest($file);
        }

        // ensure we have a valid file either provided or retrived from the request
        if (is_null($file) || ! $file instanceof SymfonyFile) {
            throw new FileNotFoundException('File not found or not an instance of ' . SymfonyFile::class);
        }

        // get the file contents to hash and presist
        $content = file_get_contents($file->getRealPath());

        // optional parameters
        $params = [
                'name'         => array_get($options, 'name', $this->guessName($file)),
                'mime_type'    => $file->getMimeType(),
                'byte_size'    => $file->getSize(),
                'content_hash' => sha1($content),
            ] + $options;

        // move file to storage
        $handle = $this->storage->write($content, $params);

        // data to persist in the repository
        $data = [
                'storage_engine' => $this->storage->getEngineName(),
                'storage_format' => $this->storage->getEngineFormat(),
                'storage_handle' => $handle,
            ] + $params;

        // finally save it
        $file = $this->repository->create($data);

        // fire event persisted

        return $file;
    }

    public function read($handle, array $options = [])
    {
        // if a given handle is numeric we assume that it's a value in our repository
        if (is_numeric($handle)) {
            $file = $this->repository->findById($handle);

            if (is_null($file)) {
                throw new FileNotFoundException("File '{$handle}' not found in the repository.'");
            }

            $handle = $file->getStorageHandle();
        } else {
            throw new \Exception('Not implemented.');
        }

        // get the file data
        $data = $this->storage->read($handle);

        if (isset($options['response']) && (bool) $options['response']) {
            return $this->generateResponse($data, $file);
        }
    }

    /**
     * Retrive a file from the current request.
     *
     * @param string $key
     *
     * @return SymfonyFile|null
     */
    protected function retriveFromRequest($key)
    {
        $files = $this->fileBag->all();

        if (isset($files[$key])) {
            return $files[$key];
        }

        return null;
    }

    protected function generateResponse($data, FileContract $file, $cache = true)
    {
        $response = new Response($data, 200, [
            'Content-Type'   => $file->getMimeType(),
            'Content-Length' => $file->getByteSize(),
        ]);

        if ($cache) {
            $response->setCache([
                'etag'          => md5($data),
                'public'        => true,
                'last_modified' => $file->getCreatedAt(),
                'max_age'       => 30 * 24 * 60 * 60,
            ]);
        }

        return $response;
    }

    /**
     * Get the filename depending on the given instance.
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     *
     * @return null|string
     */
    protected function guessName(SymfonyFile $file)
    {
        if ($file instanceof SymfonyUploadedFile) {
            return $file->getClientOriginalName();
        }

        return $file->getFilename();
    }
}
