<?php

namespace devtoolboxuk\engrafo\xmlbuilder;

use SimpleXmlIterator;

class XmlReader
{

    /**
     * Convert XML to PHP array
     * @param string $xmlString
     * @return array
     */
    public function convertXML($xmlString)
    {
        $xmlObject = new SimpleXmlIterator($xmlString);
        return json_decode(json_encode((array)$xmlObject), true);
    }

}