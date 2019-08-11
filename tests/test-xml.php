<?php

namespace devtoolboxuk\engrafo;

use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
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


    public function testWriteXml()
    {
        $options = [
            'adapter' => 'xml',
            'rootName' => 'products',
            'fileName' => 'basic.xml',
            'path' => __DIR__ . '/',
        ];

        $engrafoService = new EngrafoService($options);
        $xmlService = $engrafoService->getAdapter();

        $xmlService->openFile();
        $data = $xmlService->writeData($this->fileData, 'product');
        $xmlService->writeFile($data);
        $data = $xmlService->writeData($this->fileData, 'product');
        $xmlService->writeFile($data);
        $xmlService->closeFile();

        $this->readSimpleXml($options);
    }

    function readSimpleXml($options)
    {

        $engrafoService = new EngrafoService($options);
        $xmlService = $engrafoService->getAdapter();
        $this->assertFileExists($options['path'].$options['fileName']);
        $fileData = $xmlService->readXmlFile();

        $xmlService->deleteFileIfExists($options['path'].$options['fileName']);

        $this->assertArrayHasKey('product',$fileData);
        $this->assertEquals(123,$fileData['product'][0]['id']);
        $this->assertArrayHasKey('price',$fileData['product'][0]);
        $this->assertEquals(123,$fileData['product'][1]['id']);
        $this->assertArrayHasKey('price',$fileData['product'][1]);

        $this->assertFileNotExists($options['path'].$options['fileName']);
    }

}
