<?php

namespace Ipalaus\File\Contracts;

interface Repository
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
}
