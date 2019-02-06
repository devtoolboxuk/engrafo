<?php

namespace devtoolboxuk\engrafo;

interface FileInterface
{
    public function openFile($tags);

    public function writeData($tags, $element_name);

    public function setCdata($value);

    public function closeFile();
}