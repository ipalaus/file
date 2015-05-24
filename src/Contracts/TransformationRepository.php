<?php

namespace Ipalaus\File\Contracts;

interface TransformationRepository
{
    /**
     * Create a file transformation in the repository.
     *
     * @param  array $data
     *
     * @return \Ipalaus\File\Contracts\File
     */
    public function create(array $data);

    /**
     * Find a file transformation in the repository by id.
     *
     * @param int $id
     *
     * @return \Ipalaus\File\Contracts\File|null
     */
    public function findById($id);
}
