<?php

namespace Subugoe\IIIFBundle\Service;

use League\Flysystem\FilesystemOperator;

/**
 * Wrapper around cache and source filesystems.
 */
interface FileServiceInterface
{
    public function getCacheFilesystem(): FilesystemOperator;

    public function getSourceFilesystem(): FilesystemOperator;
}
