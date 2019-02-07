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

    function defineTags($arr)
    {
        $arr2 = [];
        foreach ($arr as $key => $value) {

            if (is_array($value)) {

                if ($key === '@a' || $key === '@attributes') {
                    $arr2 = $this->defineTags($value);
                } else {
                    $arr2[$key] = $this->defineTags($value);
                }
            } else {
                if ($key === '@v' || $key === '@value') {
                    return $value;
                }
                $arr2[$key] = $value;
            }
        }
        return $arr2;
    }


    private function formatData($data, $pretty = false)
    {
        $data = $this->defineTags($data);

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