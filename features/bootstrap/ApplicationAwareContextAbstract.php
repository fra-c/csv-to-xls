<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class ApplicationAwareContextAbstract implements Context
{
    /**
     * @var App
     */
    private $app;

    /**
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param array $headers
     *
     * @return ResponseInterface
     */
    protected function request(string $method, string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        $environment = Environment::mock(['REQUEST_METHOD' => strtoupper($method), 'REQUEST_URI' => $uri]);

        $request = Request::createFromEnvironment($environment);

        if (!empty($params)) {
            $request = $request->withParsedBody($params);
        }

        foreach ($headers as $name => $value) {
            $request = $request->withAddedHeader($name, $value);
        }

        return $this->getApp()->process($request, new Response());
    }

    /**
     * @return App
     */
    protected function getApp(): App
    {
        if (empty($this->app)) {
            $this->app = require __DIR__.'/../../app/app.php';
        }

        return $this->app;
    }

    /**
     * Decodes a JSON response to array.
     *
     * @param Response $response
     *
     * @return array
     */
    protected function decodeJsonResponse(Response $response): array
    {
        return json_decode($response->getBody(), true);
    }

    /**
     * Convert a table to a CSV string.
     *
     * @param TableNode $table
     *
     * @return string
     */
    protected function convertTableToCsv(TableNode $table): string
    {
        $rows = array_map(
            function ($row) {
                // Wrap each field with quotes
                array_walk($row, function(&$field) {
                    $field = '"' . $field . '"';
                });
                return implode(',', $row);
            },
            $table->getRows()
        );

        // Add line feed at end of file
        return implode(PHP_EOL, $rows) . PHP_EOL;
    }
}
