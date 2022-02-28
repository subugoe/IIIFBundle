<?php

namespace Subugoe\IIIFBundle\Service;

use League\Flysystem\FilesystemOperator;

/**
 * Wrapper around cache and source filesystems.
 */
class FileService implements FileServiceInterface
{
    private FilesystemOperator $cacheFilesystem;

    private FilesystemOperator $sourceFilesystem;

    public function __construct(FilesystemOperator $cacheFilesystem, FilesystemOperator $sourceFilesystem)
    {
        $this->cacheFilesystem = $cacheFilesystem;
        $this->sourceFilesystem = $sourceFilesystem;
    }

    public function getCacheFilesystem(): FilesystemOperator
    {
        return $this->cacheFilesystem;
    }

    public function getSourceFilesystem(): FilesystemOperator
    {
        return $this->sourceFilesystem;
    }
}
