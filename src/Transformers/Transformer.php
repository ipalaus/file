<?php

namespace Ipalaus\File\Transformers;

use Ipalaus\File\Contracts\File as FileContract;
use Ipalaus\File\File;

abstract class Transformer
{
    /**
     * File entity contract.
     *
     * @var \Ipalaus\File\Contracts\File
     */
    protected $entity;

    /**
     * File instance.
     *
     * @var \Ipalaus\File\File
     */
    protected $file;

    /**
     * Create a new thumbnail transfomer instance.
     *
     * @param \Ipalaus\File\Contracts\File $entity
     * @param \Ipalaus\File\File           $file
     */
    public function __construct(FileContract $entity, File $file)
    {
        $this->entity = $entity;
        $this->file = $file;
    }
}
