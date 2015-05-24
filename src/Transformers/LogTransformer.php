<?php

namespace Ipalaus\File\Transformers;

use Ipalaus\File\Contracts\Transformer as TransformerContract;

class LogTransformer extends Transformer implements TransformerContract
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'log';
    }

    /**
     * {@inheritDoc}
     */
    public function transform()
    {
        \Log::debug('hey!!');
    }
}
