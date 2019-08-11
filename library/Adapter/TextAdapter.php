<?php

namespace devtoolboxuk\engrafo\Adapter;

class TextAdapter extends AbstractAdapter implements AdapterInterface
{
    private $newLine = "\r\n";

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setSeparator(",");
    }

    public function openFile()
    {
        $this->openLocalFile($this->path . $this->fileName);
    }

    public function setHeader($header)
    {
    }

    public function writeData($tags = [], $element_name = '')
    {
        $data = $this->utilityService->arrays()->arrayValuesRecursive($tags);

        foreach ($data as $value) {
            if ($value) {
                $this->writeFile($value . $this->newLine);
            }
        }
        return;
    }

    public function getCdata($value)
    {
        return $this->setCdata($value);
    }

    public function setCdata($value)
    {
        return $value;
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

        foreach (explode(PHP_EOL, $this->readFileData) as $data) {
            if ($data != '') {
                //Remove control characters
                $csvData[] = trim(preg_replace( '/[[:cntrl:]]/', '',$data));
            }
        }

        return $csvData;
    }

}