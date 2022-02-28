<?php

namespace Subugoe\IIIFBundle\Tests\Service;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use Subugoe\IIIFBundle\Service\FileService;

class FileServiceTest extends TestCase
{
    protected FileService $fileService;

    protected function setUp(): void
    {
        $cache = new Filesystem(new InMemoryFilesystemAdapter());
        $source = clone $cache;

        $this->fileService = new FileService($cache, $source);
    }

    public function testRetrievalOfCacheFilesystemReturnsFilesystem(): void
    {
        $this->assertInstanceOf(FilesystemOperator::class, $this->fileService->getCacheFilesystem());
        $this->assertInstanceOf(FilesystemOperator::class, $this->fileService->getSourceFilesystem());
    }
}
