<?php

namespace Subugoe\IIIFBundle\Service;

use League\Flysystem\Filesystem;

/**
 * Wrapper around cache and source filesystems.
 */
interface FileServiceInterface
{
    public function getCacheFilesystem(): Filesystem;

    public function getSourceFilesystem(): Filesystem;
}
