<?php

namespace devtoolboxuk\engrafo;

use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
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

    function testWriteJson()
    {
        $options = [
            'adapter' => 'json',
            'rootName' => 'products',
            'fileName' => 'basic.json',
            'path' => __DIR__ . '/',
        ];

        $engrafoService = new EngrafoService($options);
        $jsonService = $engrafoService->getAdapter();

        $jsonService->openFile();

        $data = $jsonService->writeData($this->fileData, 'product');
        $jsonService->writeFile($data);

        $jsonService->setSeparator("");
        $data = $jsonService->writeData($this->fileData, 'product');
        $jsonService->writeFile($data);

        $jsonService->closeFile();

        $this->readJson($options);
    }

    private function readJson($options)
    {
        $engrafoService = new EngrafoService($options);
        $jsonService = $engrafoService->getAdapter();

        $fileData =  $jsonService->readFile();
        print_r($fileData);
    }

}
