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
        $fileData = $xmlService->readXmlFile();
        print_r($fileData);
    }

}
