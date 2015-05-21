<?php

namespace Ipalaus\File\Repository;

use Illuminate\Database\Eloquent\Model;
use Ipalaus\File\Contracts\File;

class Eloquent extends Model implements File
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'files';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
        'mime_type',
        'byte_size',
        'storage_engine',
        'storage_format',
        'storage_handle',
        'secret',
        'user_id',
        'meta_data',
        'is_explicit_upload',
        'content_hash',
    ];

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * {@inheritDoc}
     */
    public function getMimeType()
    {
        return $this->getAttribute('mime_type');
    }

    /**
     * {@inheritDoc}
     */
    public function getByteSize()
    {
        return $this->getAttribute('byte_size');
    }

    /**
     * {@inheritDoc}
     */
    public function getStorageEngine()
    {
        return $this->getAttribute('storage_engine');
    }

    /**
     * {@inheritDoc}
     */
    public function getStorageFormat()
    {
        return $this->getAttribute('storage_format');
    }

    /**
     * {@inheritDoc}
     */
    public function getStorageHandle()
    {
        return $this->getAttribute('storage_handle');
    }

    /**
     * {@inheritDoc}
     */
    public function getUserId()
    {
        return $this->getAttribute('user_id');
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaData()
    {
        return $this->getAttribute('meta_data');
    }

    /**
     * {@inheritDoc}
     */
    public function getIsExplicitUpload()
    {
        return $this->getAttribute('is_explicit_upload');
    }

    /**
     * {@inheritDoc}
     */
    public function getContentHash()
    {
        return $this->getAttribute('content_hash');
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->getAttribute('created_at');
    }
}
