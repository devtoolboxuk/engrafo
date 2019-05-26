<?php

namespace devtoolboxuk\engrafo;

use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
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

    function testWriteTxt()
    {
        $options = [
            'adapter' => 'text',
            'rootName' => 'products',
            'fileName' => 'basic.txt',
            'path' => __DIR__ . '/',
        ];

        $engrafoService = new EngrafoService($options);
        $txtService = $engrafoService->getAdapter();

        $txtService->openFile();

        $data = $txtService->writeData($this->fileData);
        $txtService->writeFile($data);

        $data = $txtService->writeData($this->fileData);
        $txtService->writeFile($data);

        $txtService->closeFile();

        $this->readTxt($options);
    }

    private function readTxt($options)
    {
        $engrafoService = new EngrafoService($options);
        $txtService = $engrafoService->getAdapter();

        $fileData = $txtService->readFile();
    }

}
