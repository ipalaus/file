<?php

namespace Ipalaus\File\Contracts;

interface File
{
    public function getName();
    public function getMimeType();
    public function getByteSize();
    public function getStorageEngine();
    public function getStorageFormat();
    public function getStorageHandle();
    public function getUserId();
    public function getMetaData();
    public function getIsExplicitUpload();
    public function getContentHash();
    public function getCreatedAt();
}
