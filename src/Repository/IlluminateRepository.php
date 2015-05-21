<?php

namespace Ipalaus\File\Repository;

use Ipalaus\File\Contracts\Repository;

class IlluminateRepository implements Repository
{
    /**
     * The Eloquent model to use.
     *
     * @var string
     */
    protected $model = Eloquent::class;

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
