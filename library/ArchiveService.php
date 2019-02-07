<?php

namespace devtoolboxuk\engrafo;

use ZipArchive;

class ArchiveService extends AbstractFileService
{
    private $archive;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->archive = new ZipArchive();
    }

    public function archiveFile($file, $archiveName = null)
    {

        if (!$archiveName) {
            $archiveName = sprintf('%s.zip', $this->path . $file);
        } else {
            $archiveName = $this->path . $archiveName;
        }

        $result = $this->archive->open($archiveName, \ZIPARCHIVE::CREATE);

        if (!$result) {
            throw new \Exception(sprintf('Failed to open zip archive: %s', $result));
        }

        if (!$this->archive->addFile($this->path . $file, $file)) {
            throw new \Exception(sprintf('Failed to add file %s to zip archive %s', $file, $archiveName));
        }

        if (!$this->archive->close()) {
            throw new \Exception('Failed to close zip archive.');
        }

        return true;
    }
}