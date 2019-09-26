<?php

namespace devtoolboxuk\engrafo\Adapter;

use devtoolboxuk\engrafo\xmlbuilder\XmlBuilder;

class XmlAdapter extends AbstractAdapter implements AdapterInterface
{
    private $writeXMLService;
    private $readXMLService;
    private $xmlBuildService;

    /**
     * XmlAdapter constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->xmlBuildService = new XmlBuilder();
        $this->writeXMLService = $this->xmlBuildService->writeXMLService();
        $this->readXMLService = $this->xmlBuildService->readXMLService();
    }

    /**
     * @param array $tags
     */
    public function openFile($tags = [])
    {
        $this->openLocalFile($this->path . $this->fileName);

        $this->writeXMLService->setRootName($this->rootName);
        $this->writeXMLService->setEncoding($this->encoding);
        $this->writeXMLService->setVersion($this->version);

        if (!empty($tags)) {
            $data = $this->writeXMLService->createDoc($tags);
        } else {
            $data = $this->writeXMLService->createDoc();
        }

        $data = $this->formatData($data);

        return $this->writeFile($data);
    }

    /**
     * @param $data
     * @return string|string[]|null
     */
    private function formatData($data)
    {
        if (strtolower($this->debug) != 'y') {
            $data = $this->xmlBuildService->formatXML($data);
        }
        return $data;
    }

    /**
     * @param string $type
     * @param null $file
     * @return array
     * @throws \Exception
     */
    public function readXmlFile($type = 'default', $file = null)
    {
        $this->readFileChunked($file);

        switch ($type) {
            case 'alt':
                return $this->readXml($this->readFileData);
                break;
            default:
                return $this->readSimpleXml($this->readFileData);
                break;
        }
    }


    /**
     * @param $data
     * @return array
     */
    public function readXml($data)
    {
        return $this->readXMLService->convertXML($data);
    }

    /**
     * @param $data
     * @return array
     */
    public function readSimpleXml($data)
    {
        return $this->readXMLService->convertSimpleXML($data);
    }

    /**
     * @param $data
     * @return array
     */
    public function readData($data)
    {
        return $this->readSimpleXml($data);
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

    /**
     * @param $value
     * @return string
     */
    public function setCdata($value)
    {
        if (!empty($value)) {
            return $this->writeXMLService->cdata($value);
        }
        return $value;
    }

    /**
     * @param string $tags
     * @param string $element_name
     */
    public function writeData($tags = '', $element_name = '')
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