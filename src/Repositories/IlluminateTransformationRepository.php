<?php

namespace Ipalaus\File\Repositories;

use Ipalaus\File\Contracts\TransformationRepository;

class IlluminateTransformationRepository implements TransformationRepository
{
    /**
     * The Eloquent model to use.
     *
     * @var string
     */
    protected $model = TransformationEloquent::class;

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
