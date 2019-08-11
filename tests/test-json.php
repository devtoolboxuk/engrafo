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
        $this->assertFileExists($options['path'].$options['fileName']);
        $fileData =  $jsonService->readFile();
        $jsonService->deleteFileIfExists($options['path'].$options['fileName']);

        $this->assertEquals(123,$fileData[0]['id']);
        $this->assertArrayHasKey('price',$fileData[0]);
        $this->assertEquals(123,$fileData[1]['id']);
        $this->assertArrayHasKey('price',$fileData[1]);
        $this->assertFileNotExists($options['path'].$options['fileName']);
    }

}
