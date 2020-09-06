<?php

namespace devtoolboxuk\engrafo\Adapter;

class JsonAdapter extends AbstractAdapter implements AdapterInterface
{

    private $removeComma = false;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setSeparator(",");
    }

    public function openFile($header = '[')
    {
        $this->openLocalFile($this->path . $this->fileName);
        $this->setHeader($header);
    }

    public function setHeader($header)
    {
        $this->writeFile($header);
    }

    public function writeData($tags = [], $element_name = '')
    {
        foreach ($tags as $tag) {
            $data = $this->formatData($tag);
            return $this->writeFile($data . $this->separator);
        }
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

    public function closeFile($footer = ']')
    {
        $this->setFooter($footer);
        return $this->closeLocalFile();
    }

    public function setFooter($footer)
    {
        $this->writeFile($footer);
    }

    /**
     * @param null $file
     * @return mixed|null
     * @throws \Exception
     */
    public function readFile($file = null)
    {
        $this->readFileChunked($file);

        if ($this->removeComma) {
            $this->readFileData = $this->removeTrailingCommas($this->readFileData);
        }

        if ($this->isJson()) {
            return json_decode(json_encode(json_decode($this->readFileData)), true);
        }

        return null;
    }

    private function removeTrailingCommas($json)
    {
        $json = preg_replace('/,\s*([\]}])/m', '$1', $json);
        return $json;
    }


    public function setRemoveCommas()
    {
        $this->removeComma = true;
    }

    public function unsetRemoveCommas()
    {
        $this->removeComma = false;
    }

    private function isJson()
    {
        json_decode($this->readFileData);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}