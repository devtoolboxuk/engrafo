<?php

namespace devtoolboxuk\engrafo;

use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase
{
    private $fileData;

    function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->fileData = $this->getTestData();
    }

    private function getTestData()
    {
        /** @noinspection PhpIncludeInspection */
        return include __DIR__ . '/filedata.php';
    }

    function testWriteCsv()
    {
        $options = [
            'adapter' => 'csv',
            'rootName' => 'products',
            'fileName' => 'basic.csv',
            'path' => __DIR__ . '/',
        ];

        $engrafoService = new EngrafoService($options);
        $csvService = $engrafoService->getAdapter();

        $csvService->openFile();

        $data = $csvService->writeData($this->fileData);
        $csvService->writeFile($data);

        $data = $csvService->writeData($this->fileData);
        $csvService->writeFile($data);

        $csvService->closeFile();

        $this->readCsv($options);
    }

    private function readCsv($options)
    {
        $engrafoService = new EngrafoService($options);
        $csvService = $engrafoService->getAdapter();
        $this->assertFileExists($options['path'] . $options['fileName']);
        $fileData = $csvService->readFile();
        $csvService->deleteFileIfExists($options['path'] . $options['fileName']);

        $this->assertEquals(123, $fileData[0][0]);
        $this->assertEquals('Y', $fileData[0][1]);
        $this->assertEquals('test', $fileData[0][2]);

        $this->assertEquals(123, $fileData[1][0]);
        $this->assertEquals('Y', $fileData[1][1]);
        $this->assertEquals('test', $fileData[1][2]);
        $this->assertFileNotExists($options['path'] . $options['fileName']);
    }

}
