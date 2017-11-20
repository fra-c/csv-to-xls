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
use PDO;
use ReflectionProperty;

/**
 * This class is not tested.
 *
 * It is here as an implementation example to log the saved file into a database.
 */
class FilePdoRepository implements FileRepository
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * FilePdoRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * {@inheritdoc}
     */
    public function save(File $file): void
    {
        $stm = $this->pdo->prepare('INSERT INTO file_log (id, filename, created_at) VALUES(?, ?, ?)');
        $stm->execute([
            $file->getId(),
            $file->getFilename(),
            $file->getCreatedAt()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById(string $id): File
    {
        $stm = $this->pdo->prepare('SELECT FROM file_log WHERE id = ?');
        $stm->execute([$id]);

        return $this->hydrateFileEntity($stm->fetch());
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        $stm = $this->pdo->prepare('SELECT FROM file_log');
        $stm->execute();

        return array_map(
            function(array $file) {
                return $this->hydrateFileEntity($file);
            },
            $stm->fetchAll()
        );
    }

    /**
     * Hydrate the array result to a File object.
     *
     * @param array $file
     *
     * @return File
     */
    private function hydrateFileEntity(array $file): File
    {
        $fileEntity = File::withFilename($file['filename']);

        $idProperty = new ReflectionProperty(File::class, 'id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($fileEntity, $file['id']);

        $createdAtProperty = new ReflectionProperty(File::class, 'createdAt');
        $createdAtProperty->setAccessible(true);
        $createdAtProperty->setValue($fileEntity, $file['created_at']);

        return $fileEntity;
    }
}
