<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

use App\Action\GetFileInfoAction;
use App\Action\ListFilesAction;
use App\Action\SaveFileAction;
use App\Repository\FileMemoryRepository;
use App\Repository\FileRepository;
use App\Serializer\FileJsonSerializer;
use App\Serializer\FileSerializer;
use App\Writer\Writer;
use App\Writer\XlsWriter;
use Slim\Container;

return [
    'config' => [
        'files_path' => __DIR__.'/../.data/files',
        'temp_path' => __DIR__.'/../.data/tmp',
    ],

    FileSerializer::class => function(Container $container) {
        return new FileJsonSerializer($container->get('config')['files_path']);
    },

    FileRepository::class => function () {
        /*
         * In a real life scenario we would use 'FilePdoRepository' but for the purpose of the test let's save it in memory.
         */
        return new FileMemoryRepository();
    },

    Writer::class => function (Container $container) {
        return new XlsWriter($container->get('config')['files_path'], $container->get('config')['temp_path']);
    },

    SaveFileAction::class => function (Container $container) {
        return new SaveFileAction(
            $container->get(Writer::class),
            $container->get(FileRepository::class),
            $container->get(FileSerializer::class)
        );
    },

    ListFilesAction::class => function (Container $container) {
        return new ListFilesAction(
            $container->get(FileRepository::class),
            $container->get(FileSerializer::class)
        );
    },

    GetFileInfoAction::class => function (Container $container) {
        return new GetFileInfoAction(
            $container->get(FileRepository::class),
            $container->get(FileSerializer::class)
        );
    }
];
