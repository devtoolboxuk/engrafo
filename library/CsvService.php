<?php

namespace devtoolboxuk\engrafo;

class CsvService extends AbstractFileService implements FileInterface
{

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setSeparator(",");
    }

    public function openFile($tags)
    {
        $this->openLocalFile($this->path . $this->fileName);
    }

    public function writeData($tags, $element_name)
    {
        foreach ($tags as $tag) {
            $data = $this->formatData($tag);
            return $this->writeFile($data . $this->separator);
        }
    }

    private function formatData($data)
    {
        return $data;
    }

    public function setCdata($value)
    {
        return $value;
    }

    public function closeFile()
    {
        return $this->closeLocalFile();
    }

    public function setHeader($header)
    {
        $this->writeFile($header);
    }

    public function setFooter($footer)
    {
        $this->writeFile($footer);
    }

    /**
     * @todo -
     * @return array
     */
    public function readData()
    {
        $fileData = [];

        if (false !== $handle = fopen($this->path . $this->fileName, "r")) {
            while (false !== $data = fgetcsv($handle)) {
                $fileData[] = $data;
            }
            fclose($this->path . $this->fileName);
        }

        return $fileData;
    }

    public function getWriteService()
    {
    }


    public function writeRawData($data, $pretty = false)
    {
        $data = $this->formatData($data, $pretty);
        return $this->writeFile($data . $this->separator);
    }

}