<?php

namespace devtoolboxuk\engrafo;

use devtoolboxuk\engrafo\xmlbuilder\XmlBuilder;

class XmlService extends AbstractFileService implements FileInterface
{
    private $readFileData;
    private $writeXMLService;
    private $readXMLService;
    private $xmlBuildService;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->xmlBuildService = new XmlBuilder();
        $this->writeXMLService = $this->xmlBuildService->writeXMLService();
        $this->readXMLService = $this->xmlBuildService->readXMLService();
    }

    public function readXmlFile($file, $name)
    {
        $this->readFileChunked($file);

        if (!$this->readFileData) {
            throw new \Exception(sprintf("Unable to read '%s'.", $file));
        }

        return $this->readXMLService->convertXML($this->readFileData, $name);
    }

    private function readFileChunked($filename)
    {
        $chunkSize = 512; // bytes per chunk
        $handle = fopen($filename, "rb");

        if ($handle === false) {
            return false;
        }
        $this->readFileData = '';
        while (!feof($handle)) {
            $this->readFileData .= fread($handle, $chunkSize);
            ob_flush();
            flush();
        }

        return fclose($handle);
    }

    /**
     * Used for some legacy code that I wrote... this will vanish at some point
     * @param $value
     * @return string
     */
    public function getCdata($value)
    {
        return $this->setCdata($value);
    }

    public function setCdata($value)
    {
        if (!empty($value)) {
            return $this->writeXMLService->cdata($value);
        }
        return $value;
    }

    public function openFile($tags)
    {
        $this->openLocalFile($this->path . $this->fileName);

        $this->writeXMLService->setRootName($this->rootName);
        $data = $this->writeXMLService->createDoc($tags);

        $data = $this->formatData($data);

        return $this->writeFile($data);
    }

    private function formatData($data)
    {
        if (strtolower($this->debug) != 'y') {
            $data = $this->xmlBuildService->formatXML($data);
        }
        return $data;
    }

    public function writeData($tags, $element_name)
    {
        $data = $this->writeXMLService->create($element_name, $tags);
        $data = $this->formatData($data);
        return $this->writeFile($data);
    }

    public function closeFile()
    {
        $data = $this->writeXMLService->endDoc();
        $data = $this->formatData($data);
        $this->writeFile($data);
        return $this->closeLocalFile();
    }


}