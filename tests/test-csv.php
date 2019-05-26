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

        $fileData = $csvService->readFile();
    }

}
