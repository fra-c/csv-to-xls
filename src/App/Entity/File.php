<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

namespace App\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class File
{
    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * File constructor.
     *
     * @param string $filename
     */
    private function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * Create a file entity with the specified filename.
     *
     * @param string $filename
     *
     * @return File
     */
    public static function withFilename(string $filename): self
    {
        return new static($filename);
    }

    /**
     * Retrieve the filename.
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Retrieve the id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Retrieve the creation date.
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
