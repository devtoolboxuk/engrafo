<?php

namespace devtoolbox\engrafo;

interface FileInterface
{
    public function openFile($tags);

    public function writeData($tags, $element_name);

    public function closeFile();
}