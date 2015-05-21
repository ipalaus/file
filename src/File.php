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
     * Create a file.
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

        // write the file to the storage with the injected storage engine
        $handle = $this->storage->write($content, $params);

        // data to persist into the repository
        $data = [
                'storage_engine' => $this->storage->getEngineName(),
                'storage_format' => $this->storage->getEngineFormat(),
                'storage_handle' => $handle,
                'secret'         => $this->generateSecret(),
            ] + $params;

        // finally save it
        $file = $this->repository->create($data);

        return $file;
    }

    /**
     * Read a handle.
     *
     * @param int|array $attributes
     * @param array     $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Ipalaus\File\Contracts\FileNotFoundException
     */
    public function read($attributes, array $options = [])
    {
        // if a numeric attribute it's given we are probably trying to access to a primary key
        if (is_numeric($attributes)) {
            $attributes = ['id' => $attributes];
        }

        $file = $this->repository->findByAttributes($attributes);

        if (is_null($file)) {
            throw new FileNotFoundException('File ' . json_encode($attributes) . ' not found in the repository.');
        }

        // get the file data
        $data = $this->storage->read($file->getStorageHandle());

        // return a generated response with the file contents
        if (isset($options['response']) && (bool) $options['response']) {
            return $this->generateResponse($data, $file);
        }

        return $data;
    }

    /**
     * Response with a given handle.
     *
     * @param int|string $id
     * @param array      $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Ipalaus\File\Contracts\FileNotFoundException
     */
    public function response($id, array $options = [])
    {
        $options['response'] = true;

        return $this->read($id, $options);
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

    /**
     * Generate a Symfony Response with the given raw data. Additionally,
     * you can add the cache headers to the response (on by default).
     *
     * @param string                       $data
     * @param \Ipalaus\File\Contracts\File $file
     * @param bool                         $cache
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Generate a random alpha-numeric string.
     *
     * @param int $length
     *
     * @return string
     */
    protected function generateSecret($length = 16)
    {
        if ( ! function_exists('openssl_random_pseudo_bytes')) {
            throw new \RuntimeException('OpenSSL extension is required.');
        }

        $bytes = openssl_random_pseudo_bytes($length * 2);

        if ($bytes === false) {
            throw new \RuntimeException('Unable to generate random string.');
        }

        return substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
    }
}
