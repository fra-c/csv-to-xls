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
use Slim\App;

$app = new App(require __DIR__ . '/services.php');

/**
 * Save a file
 */
$app->post('/', SaveFileAction::class);

/**
 * List all saved files
 */
$app->get('/', ListFilesAction::class);

/**
 * Retrieve info of a file
 */
$app->get('/{id}', GetFileInfoAction::class);

return $app;
