<?php

namespace Subugoe\IIIFBundle\Service;

use League\Flysystem\Filesystem;

/**
 * Wrapper around cache and source filesystems.
 */
class FileService implements FileServiceInterface
{
    private Filesystem $cacheFilesystem;

    private Filesystem $sourceFilesystem;

    public function __construct(Filesystem $cacheFilesystem, Filesystem $sourceFilesystem)
    {
        $this->cacheFilesystem = $cacheFilesystem;
        $this->sourceFilesystem = $sourceFilesystem;
    }

    public function getCacheFilesystem(): Filesystem
    {
        return $this->cacheFilesystem;
    }

    public function getSourceFilesystem(): Filesystem
    {
        return $this->sourceFilesystem;
    }
}
