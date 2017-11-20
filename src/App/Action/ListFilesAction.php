<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

namespace App\Action;

use App\Repository\FileRepository;
use App\Serializer\FileSerializer;
use Slim\Http\Request;
use Slim\Http\Response;

class ListFilesAction
{
    /**
     * @var FileRepository
     */
    private $fileRepository;
    /**
     * @var FileSerializer
     */
    private $fileSerializer;

    public function __construct(FileRepository $fileRepository, FileSerializer $fileSerializer)
    {
        $this->fileRepository = $fileRepository;
        $this->fileSerializer = $fileSerializer;
    }

    /**
     * List all the saved files.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args = []): Response
    {
        $files = $this->fileRepository->getAll();

        return $response
            ->withStatus(200)
            ->write($this->fileSerializer->serializeFileArray($files));
    }
}
