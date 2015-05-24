<?php

namespace Ipalaus\File\Repositories;

use Illuminate\Database\Eloquent\Model;
use Ipalaus\File\Contracts\File;

class TransformationEloquent extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'file_transformations';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'original_id',
        'transformed_id',
        'transform',
    ];

    /**
     * Relationship with Files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(FileEloquent::class, 'transformed_id');
    }
}
