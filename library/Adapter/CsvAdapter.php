<?php

namespace devtoolboxuk\engrafo\Adapter;

use League\Csv\Writer;

class CsvAdapter extends AbstractAdapter implements AdapterInterface
{
    private $csvBuildService;
    private $newLine = "\r\n";

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setSeparator(",");

        $this->csvBuildService = Writer::createFromPath($this->path . $this->fileName, 'c');

        $this->csvBuildService->setDelimiter($this->separator); //the delimiter will be the tab character
        $this->csvBuildService->setNewline($this->newLine);
    }

    public function openFile()
    {
        $this->openLocalFile($this->path . $this->fileName);
    }

    public function setHeader($header)
    {
        $data = $this->utilityService->arrays()->arrayValuesRecursive($header);
        $this->csvBuildService->insertOne($data);
    }

    public function writeData($tags = [], $element_name = '')
    {
        $data = $this->utilityService->arrays()->arrayValuesRecursive($tags);
        $this->csvBuildService->insertOne($data);
    }

    public function getCdata($value)
    {
        return $this->setCdata($value);
    }

    public function setCdata($value)
    {
        return $value;
    }

    public function writeRawData($data)
    {
        $data = $this->formatData($data);
        return $this->writeFile($data . $this->separator);
    }

    private function formatData($data)
    {
        $data = $this->defineTags($data);

        if ($this->debug) {
            $data = json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data = json_encode($data);
        }

        return $data;
    }

    private function defineTags($arr)
    {
        $arr2 = [];
        foreach ($arr as $key => $value) {

            if (is_array($value)) {

                if ($key === '@a' || $key === '@attributes') {
                    $arr2 = $this->defineTags($value);
                } else {
                    $arr2[] = $this->defineTags($value);
                }
            } else {
                if ($key === '@v' || $key === '@value') {
                    return $value;
                }
                $arr2[] = $value;
            }
        }
        return $arr2;
    }

    public function closeFile()
    {
        return $this->closeLocalFile();
    }

    public function setFooter($footer)
    {
    }


    /**
     * @param null $file
     * @return mixed|null
     * @throws \Exception
     */
    public function readFile($file = null)
    {
        $this->readFileChunked($file);
        $csvData = [];

        foreach (explode($this->newLine, $this->readFileData) as $data) {
            if ($data != '') {
                $csvData[] = $data;
            }
        }

        foreach ($csvData as $key => $data) {
            $csvData[$key] = explode($this->separator, $data);
        }

        return $csvData;
    }
}