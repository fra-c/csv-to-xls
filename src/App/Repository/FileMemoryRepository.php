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
use App\Exception\FileNotFoundException;

class FileMemoryRepository implements FileRepository
{
    /**
     * @var File[]
     */
    private $files = [];

    /**
     * It saves a file.
     *
     * @param File $file
     */
    public function save(File $file): void
    {
        $this->files[$file->getId()] = $file;
    }

    /**
     * Retrieves a file by id or throws and exception if not found.
     *
     * @param string $id
     *
     * @return File
     *
     * @throws FileNotFoundException
     */
    public function findOneById(string $id): File
    {
        if (empty($this->files[$id])) {
            throw new FileNotFoundException(sprintf('File with id "%s" not found.', $id));
        }
        return $this->files[$id];
    }

    /**
     * Retrieves all the saved files.
     *
     * @return File[]
     */
    public function getAll(): array
    {
        return array_values($this->files);
    }
}
