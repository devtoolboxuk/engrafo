<?php

namespace devtoolboxuk\engrafo;

class JsonService extends AbstractFileService implements FileInterface
{

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setSeparator(",");
    }

    public function openFile($tags = '')
    {
        $this->openLocalFile($this->path . $this->fileName);
    }

    public function writeData($tags, $element_name = '')
    {
        foreach ($tags as $tag) {
            $data = $this->formatData($tag);
            return $this->writeFile($data . $this->separator);
        }
    }

    private function formatData($data, $pretty = false)
    {
        if ($pretty) {
            $data = json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data = json_encode($data);
        }

        return $data;
    }

    public function setHeader($header)
    {
        $this->writeFile($header);
    }

    public function setFooter($footer)
    {
        $this->writeFile($footer);
    }

    public function setCdata($value)
    {
        return $value;
    }

    public function getWriteService()
    {
    }

    public function writeRawData($data, $pretty = false)
    {
        $data = $this->formatData($data, $pretty);
        return $this->writeFile($data . $this->separator);
    }


    public function closeFile()
    {
        return $this->closeLocalFile();
    }
}