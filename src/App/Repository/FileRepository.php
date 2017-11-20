<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

namespace App\Repository;

use App\Entity\File;

interface FileRepository
{
    /**
     * It saves a file.
     *
     * @param File $file
     */
    public function save(File $file): void;

    /**
     * Retrieves a file by id or throws and exception if not found.
     *
     * @param string $id
     *
     * @return File
     */
    public function findOneById(string $id): File;

    /**
     * Retrieves all the saved files.
     *
     * @return File[]
     */
    public function getAll(): array;
}
