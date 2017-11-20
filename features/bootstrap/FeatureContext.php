<?php
/*
 * Copyright 2017 Francesco Cocchianella. All rights reserved.
 *
 * This software is proprietary and may not be copied, distributed,
 * published or used in any way, in whole or in part, without prior
 * written agreement from the author.
 */

use App\Entity\File;
use App\Repository\FileRepository;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use Ramsey\Uuid\Uuid;
use Slim\Http\Response;

class FeatureContext extends ApplicationAwareContextAbstract
{
    /**
     * @var Response
     */
    private $lastResponse;

    /**
     * @BeforeScenario
     */
    public function cleanUpDataFolder()
    {
        $config = $this->getApp()->getContainer()->get('config');

        if (file_exists($config['files_path'])) {
            $this->emptyDir($config['files_path']);
        } else {
            mkdir($config['files_path'], null, true);
        }

        if (file_exists($config['temp_path'])) {
            $this->emptyDir($config['temp_path']);
        } else {
            mkdir($config['temp_path'], null, true);
        }
    }

    private function emptyDir($path)
    {
        $files = glob($path.'/{,.}*', GLOB_BRACE);
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }
    }

    /**
     * @Given I save(d) a file with content :fileContent
     */
    public function iSaveAFileWithContent($fileContent)
    {
        $this->lastResponse = $this->request(
            'POST',
            '/',
            ['content' => str_replace('\n', PHP_EOL, $fileContent)]
        );

        Assert::assertEquals(201, $this->lastResponse->getStatusCode());
    }

    /**
     * @When I request a list of saved files
     */
    public function iRequestAListOfSavedFiles()
    {
        $this->lastResponse = $this->request('GET', '/');
    }

    /**
     * @Then I should get :numberOfEntries entries
     */
    public function iShouldGetEntries($numberOfEntries)
    {
        Assert::assertCount((int) $numberOfEntries, $this->decodeJsonResponse($this->lastResponse));
    }

    /**
     * @When I request the file info
     */
    public function iRequestTheFileInfo()
    {
        $fileId = $this->decodeJsonResponse($this->lastResponse)['id'];
        $this->lastResponse = $this->request('GET', '/' . $fileId);
    }

    /**
     * @Then an ID for the resource should be created
     * @Then I should get an ID for the resource created
     */
    public function iShouldGetAnIdForTheResourceCreated()
    {
        $lastResourceId = $this->decodeJsonResponse($this->lastResponse)['id'];

        Assert::assertRegExp(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $lastResourceId
        );
    }

    /**
     * @Then I should get a link to the saved file containing the following data:
     */
    public function iShouldGetALinkToTheSavedFileContainingTheFollowingData(TableNode $table)
    {
        $lastResource = $this->decodeJsonResponse($this->lastResponse);

        Assert::assertNotEmpty($lastResource['link']);

        $expectedCsvData = $this->convertTableToCsv($table);

        $tempFilename = $this->getApp()->getContainer()->get('config')['temp_path'] . '/' . Uuid::uuid4() . '.csv';

        $objReader = PHPExcel_IOFactory::load($lastResource['link']);
        $objWriter = PHPExcel_IOFactory::createWriter($objReader, 'CSV');
        $objWriter->save($tempFilename);

        $convertedFile = file_get_contents($tempFilename);
        unlink($tempFilename);

        Assert::assertEquals($expectedCsvData, $convertedFile);
    }

    /**
     * @Then a log entry should be saved
     */
    public function aLogEntryShouldBeSaved()
    {
        $lastResource = $this->decodeJsonResponse($this->lastResponse);

        /** @var File $file */
        $file = $this->getApp()->getContainer()->get(FileRepository::class)->findOneById($lastResource['id']);

        Assert::assertSame($lastResource['id'], $file->getId());
        Assert::assertSame($lastResource['filename'], $file->getFilename());
        Assert::assertSame($lastResource['created_at'], $file->getCreatedAt()->format(DateTime::ISO8601));
    }
}
