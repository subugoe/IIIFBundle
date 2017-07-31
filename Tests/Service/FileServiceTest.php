<?php

namespace Subugoe\IIIFBundle\Tests;

use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use PHPUnit\Framework\TestCase;
use Subugoe\IIIFBundle\Service\FileService;

class FileServiceTest extends TestCase
{
    /**
     * @var FileService
     */
    protected $fileService;

    public function setUp()
    {
        $cache = new Filesystem(new NullAdapter());
        $source = clone $cache;

        $this->fileService = new FileService($cache, $source);
    }

    public function testRetrievalOfCacheFilesystemReturnsFilesystemInterface()
    {
        $this->assertInstanceOf(FilesystemInterface::class, $this->fileService->getCacheFilesystem());
        $this->assertInstanceOf(FilesystemInterface::class, $this->fileService->getSourceFilesystem());
    }
}
