<?php

namespace Ipalaus\File\Transformers;

use Ipalaus\File\Contracts\File as FileContract;
use Ipalaus\File\File;

abstract class Transformer
{
    /**
     * File instance.
     *
     * @var \Ipalaus\File\File
     */
    protected $file;

    /**
     * File entity contract.
     *
     * @var \Ipalaus\File\Contracts\File
     */
    protected $entity;

    /**
     * File content.
     *
     * @var string
     */
    protected $content;

    /**
     * Create a new thumbnail transfomer instance.
     *
     * @param \Ipalaus\File\File           $file
     * @param \Ipalaus\File\Contracts\File $entity
     * @param string                       $content
     */
    public function __construct(File $file, FileContract $entity, $content)
    {
        $this->file = $file;
        $this->entity = $entity;
        $this->content = $content;
    }
}
