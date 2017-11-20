<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

namespace App\Writer;

use PHPExcel_IOFactory;
use Ramsey\Uuid\Uuid;

class XlsWriter implements Writer
{
    /**
     * @var string
     */
    private $filesPath;

    /**
     * @var string
     */
    private $tempPath;

    /**
     * XlsWriter constructor.
     *
     * @param string $filesPath The path where all the files will be stored.
     * @param string $tempPath A temporary folder to convert CSV to XLS
     */
    public function __construct(string $filesPath, string $tempPath)
    {
        $this->filesPath = $filesPath;
        $this->tempPath = $tempPath;
    }

    /**
     * Write a file in XLS format
     *
     * @param string $content
     * @param string $filename
     */
    public function write(string $content, string $filename): void
    {
        $tempFilename = $this->tempPath . '/' . Uuid::uuid4() . '.csv';
        file_put_contents($tempFilename, $content);

        $objReader = PHPExcel_IOFactory::load($tempFilename);
        $objWriter = PHPExcel_IOFactory::createWriter($objReader, 'Excel5');
        $objWriter->save($this->filesPath . '/' . $filename);

        unlink($tempFilename);
    }
}
