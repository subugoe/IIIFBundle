<?php

namespace Subugoe\IIIFBundle\Service;

use League\Flysystem\FilesystemOperator;

/**
 * Wrapper around cache and source filesystems.
 */
class FileService implements FileServiceInterface
{
    public function __construct(private readonly FilesystemOperator $cacheFilesystem, private readonly FilesystemOperator $sourceFilesystem)
    {
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
