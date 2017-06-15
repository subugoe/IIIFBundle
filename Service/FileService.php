<?php

namespace Subugoe\IIIFBundle\Service;

use League\Flysystem\Filesystem;
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
     * @var array
     */
    private $imageConfiguration;

    public function __construct(FilesystemInterface $cacheFilesystem, array $imageConfiguration)
    {
        $this->cacheFilesystem = $cacheFilesystem;
        $this->imageConfiguration = $imageConfiguration;
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
        $sourceAdapterConfiguration = $this->imageConfiguration['adapters']['source']['configuration'];
        $sourceAdapterClass = $this->imageConfiguration['adapters']['source']['class'];
        $sourceAdapter = new $sourceAdapterClass($sourceAdapterConfiguration);

        return new Filesystem($sourceAdapter);
    }
}
