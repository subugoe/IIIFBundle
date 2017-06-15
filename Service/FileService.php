<?php

namespace Subugoe\IIIFBundle\Service;

use League\Flysystem\FilesystemInterface;

/**
 * Wrapper around cache and source filesystems.
 */
class FileService
{
    /**
     * @var FilesystemInterface
     */
    private $cacheFilesystem;

    /**
     * @var FilesystemInterface
     */
    private $sourceFilesystem;

    public function __construct(FilesystemInterface $cacheFilesystem, FilesystemInterface $sourceFilesystem)
    {
        $this->cacheFilesystem = $cacheFilesystem;
        $this->sourceFilesystem = $sourceFilesystem;
    }

    /**
     * @return FilesystemInterface
     */
    public function getCacheFilesystem(): FilesystemInterface
    {
        return $this->cacheFilesystem;
    }

    /**
     * @return FilesystemInterface
     */
    public function getSourceFilesystem(): FilesystemInterface
    {
        return $this->sourceFilesystem;
    }
}
