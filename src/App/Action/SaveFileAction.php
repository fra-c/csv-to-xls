<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

namespace App\Action;

use App\Entity\File;
use App\Repository\FileRepository;
use App\Serializer\FileSerializer;
use App\Writer\Writer;
use Ramsey\Uuid\Uuid;
use Slim\Http\Request;
use Slim\Http\Response;
use Throwable;

class SaveFileAction
{
    /**
     * @var Writer
     */
    private $writer;
    /**
     * @var FileRepository
     */
    private $fileRepository;
    /**
     * @var FileSerializer
     */
    private $fileSerializer;

    public function __construct(
        Writer $writer,
        FileRepository $fileRepository,
        FileSerializer $fileSerializer
    ) {
        $this->writer = $writer;
        $this->fileRepository = $fileRepository;
        $this->fileSerializer = $fileSerializer;
    }

    /**
     * Save a file in a specific format based on the writer service.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args = []): Response
    {
        $file = File::withFilename(Uuid::uuid4() . '.xls');
        $postArgs = $request->getParsedBody();

        try {
            $this->writer->write($postArgs['content'], $file->getFilename());
            $this->fileRepository->save($file);
        } catch (Throwable $exception) {
            return $response->withStatus(500)->withJson(['error' => $exception->getMessage()]);
        }

        return $response
            ->withStatus(201)
            ->write($this->fileSerializer->serializeFile($file));
    }
}
