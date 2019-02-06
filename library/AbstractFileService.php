<?php

namespace devtoolboxuk\engrafo;

abstract class AbstractFileService
{
    protected $rootName;
    protected $fileName;
    protected $path;
    protected $debug;
    protected $separator;

    protected $localFileHandle;

    function __construct($options)
    {
        $this->rootName = $options['rootName'];
        $this->fileName = $options['fileName'];
        $this->debug = $options['debug'];
        $this->path = $options['path'];
    }

    public function deleteFileIfExists($file)
    {
        if (file_exists($file)) {
            try {
                unlink($file);
            } catch (\Exception $e) {
                throw new \Exception(sprintf("Failed to delete file '%s'.", $file));
            }
        }
    }

    public function getFileHandler()
    {
        return $this->localFileHandle;
    }

    public function openLocalFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
        $this->localFileHandle = fopen($file, 'a');
    }

    public function writeFile($data)
    {
        $pieces = str_split($data, 1024 * 4);
        foreach ($pieces as $piece) {
            fwrite($this->localFileHandle, $piece, strlen($piece));
        }
    }

    public function closeLocalFile()
    {
        fclose($this->localFileHandle);
    }

    protected function setSeparator($separator)
    {
        $this->separator = $separator;
    }
}