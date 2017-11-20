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

interface FileSerializer
{
    /**
     * Serialize a file entity.
     *
     * @param File $file
     *
     * @return string
     */
    public function serializeFile(File $file): string;

    /**
     * @param array $files
     *
     * @return string
     */
    public function serializeFileArray(array $files): string;
}
