<?php

namespace devtoolboxuk\engrafo\Adapter;

use devtoolboxuk\utilitybundle\UtilityService;

abstract class AbstractAdapter implements AdapterInterface
{
    protected $localFileHandle;

    protected $rootName;
    protected $fileName;
    protected $path;
    protected $debug = null;
    protected $separator;

    protected $readFileData;

    protected $chunkSize = '1024';

    protected $utilityService;

    public function __construct($options = [])
    {
        $this->rootName = isset($options['rootName']) ? $options['rootName'] : null;
        $this->fileName = isset($options['fileName']) ? $options['fileName'] : null;
        $this->chunkSize = isset($options['chunkSize']) ? $options['chunkSize'] : $this->chunkSize;
        $debug = isset($options['debug']) ? $options['debug'] : null;

        switch (strtoupper($debug)) {
            case 'Y':
                $this->debug = true;
                break;
        }

        $this->path = isset($options['path']) ? $options['path'] : null;

        $this->utilityService = new UtilityService();

    }

    public function setSeparator($separator)
    {
        $this->separator = $separator;
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

    protected function closeLocalFile()
    {
        fclose($this->localFileHandle);
    }

    protected function readFileChunked($filename)
    {

        if (!$filename) {
            $filename = $this->path . $this->fileName;
        }

        $handle = fopen($filename, "rb");

        if ($handle === false) {
            return false;
        }
        $this->readFileData = '';
        while (!feof($handle)) {
            $this->readFileData .= fread($handle, $this->chunkSize);
            ob_flush();
            flush();
        }

        fclose($handle);

        if (!$this->readFileData) {
            throw new \Exception(sprintf("Unable to read '%s'.", $filename));
        }
    }

}
