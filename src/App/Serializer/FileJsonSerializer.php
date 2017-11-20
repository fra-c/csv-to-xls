<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

namespace App\Serializer;

use App\Entity\File;
use DateTime;

class FileJsonSerializer implements FileSerializer
{
    /**
     * @var string
     */
    private $filesPath;

    /**
     * FileJsonSerializer constructor.
     *
     * @param string $filesPath
     */
    public function __construct(string $filesPath)
    {
        $this->filesPath = $filesPath;
    }

    /**
     * Serialize a file entity to a json string.
     *
     * @param File $file
     *
     * @return string
     */
    public function serializeFile(File $file): string
    {
        return json_encode($this->fileToArray($file));
    }

    /**
     * Serialize an array of files;
     *
     * @param array $files
     *
     * @return string
     */
    public function serializeFileArray(array $files): string
    {
        return json_encode(
            array_map(
                function(File $file) {
                    return $this->fileToArray($file);
                },
                $files
            )
        );
    }

    /**
     * @param File $file
     *
     * @return array
     */
    private function fileToArray(File $file): array
    {
        return [
            'id' => $file->getId(),
            'filename' => $file->getFilename(),
            'created_at' => $file->getCreatedAt()->format(DateTime::ISO8601),
            'link' => $this->filesPath . '/' . $file->getFilename()
        ];
    }
}
