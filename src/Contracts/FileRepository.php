<?php

namespace Ipalaus\File\Contracts;

interface FileRepository
{
    /**
     * Create a file in the repository.
     *
     * @param  array $data
     *
     * @return \Ipalaus\File\Contracts\File
     */
    public function create(array $data);

    /**
     * Find a file in the repository by id.
     *
     * @param int $id
     *
     * @return \Ipalaus\File\Contracts\File|null
     */
    public function findById($id);

    /**
     * Find a file in the repository with the given attributes.
     *
     * @param array $attributes
     *
     * @return \Ipalaus\File\Contracts\File|null
     */
    public function findByAttributes(array $attributes);
}
