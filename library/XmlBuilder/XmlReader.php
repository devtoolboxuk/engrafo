<?php

namespace devtoolboxuk\engrafo\xmlbuilder;

use SimpleXmlIterator;

class XmlReader
{

    /**
     * Alternative Convert XML to PHP array
     * @param string $xmlString
     * @return array
     */
    public function convertSimpleXML($xmlString)
    {
        $xmlObject = new SimpleXmlIterator($xmlString);
        return json_decode(json_encode((array)$xmlObject), true);
    }

    /**
     * Convert XML to PHP array
     * @param string $xmlString
     * @return array
     */
    public function convertXML($xmlString)
    {
        $iterator = new SimpleXmlIterator($xmlString);
        $result = [];
        foreach ($iterator as $key => $iterator) {
            $result[$key][] = $this->getArrayFromXml($iterator);
        }
        return $result;
    }

    /**
     * @param SimpleXmlIterator $iterator
     * @return array
     */
    protected function getArrayFromXml(SimpleXmlIterator $iterator)
    {
        $result = [];
        for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
            if ($iterator->hasChildren()) {
                foreach ($iterator->getChildren() as $key => $value) {
                    if (1 <= $value->count()) {
                        $result[$iterator->key()][][$key] = $this->getArrayFromXml($value);
                    } else {
                        if (true === $value->hasChildren()) {
                            $result[$iterator->key()][$key] = $this->getArrayFromXml($value);
                        } else {
                            $result[$iterator->key()] = $this->getArrayFromXml($iterator->current());
                        }
                    }
                }
            } else {
                $result[$iterator->key()] = $this->convertToStringOrBoolean($iterator->current());
            }
        }
        return $result;
    }

    /**
     * Cast to string or to a boolean value if the string contains a true or false
     * @param mixed $string
     * @return boolean|string
     */
    protected function convertToStringOrBoolean($string)
    {
        $result = (string)$string;
        $lowerString = strtolower($result);
        if ('true' === $lowerString) {
            $result = true;
        } elseif ('false' === $lowerString) {
            $result = false;
        }
        return $result;
    }


}