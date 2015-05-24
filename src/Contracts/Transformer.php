<?php

namespace Ipalaus\File\Contracts;

interface Transformer
{
    /**
     * Get the name of the current transformer.
     *
     * @return string
     */
    public function getName();

    /**
     * Run a transformation with the given entity.
     *
     * @return void
     */
    public function transform();
}
