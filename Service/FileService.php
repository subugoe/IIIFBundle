<?php

namespace Subugoe\IIIFBundle\Service;

use League\Flysystem\Filesystem;

/**
 * Wrapper around cache and source filesystems.
 */
class FileService
{
    /**
     * @var Filesystem
     */
    private $cacheFilesystem;

    /**
     * @var Filesystem
     */
    private $sourceFilesystem;

    public function __construct(Filesystem $cacheFilesystem, Filesystem $sourceFilesystem)
    {
        $this->cacheFilesystem = $cacheFilesystem;
        $this->sourceFilesystem = $sourceFilesystem;
    }

    /**
     * @return Filesystem
     */
    public function getCacheFilesystem(): Filesystem
    {
        return $this->cacheFilesystem;
    }

    /**
     * @return Filesystem
     */
    public function getSourceFilesystem(): Filesystem
    {
        return $this->sourceFilesystem;
    }
}
