<?php

namespace Ipalaus\File\Repositories;

use Ipalaus\File\Contracts\FileRepository;

class IlluminateFileRepository implements FileRepository
{
    /**
     * The Eloquent model to use.
     *
     * @var string
     */
    protected $model = FileEloquent::class;

    /**
     * Create a new illuminate repository instance..
     *
     * @param  string  $model
     */
    public function __construct($model = null) {
        if ( ! is_null($model)) {
            $this->model = $model;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data)
    {
        return $this->createModel()->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function findById($id)
    {
        return $this->createModel()->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findByAttributes(array $attributes)
    {
        return $this->createModel()->where($attributes)->first();
    }

    /**
     * Create a new model instance.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel(array $data = [])
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class($data);
    }
}
