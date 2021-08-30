<?php

namespace Subugoe\IIIFBundle\Tests\Service;

use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use Subugoe\IIIFBundle\Service\FileService;

class FileServiceTest extends TestCase
{
    /**
     * @var FileService
     */
    protected $fileService;

    protected function setUp(): void
    {
        $cache = new Filesystem(new InMemoryFilesystemAdapter());
        $source = clone $cache;

        $this->fileService = new FileService($cache, $source);
    }

    public function testRetrievalOfCacheFilesystemReturnsFilesystem()
    {
        $this->assertInstanceOf(Filesystem::class, $this->fileService->getCacheFilesystem());
        $this->assertInstanceOf(Filesystem::class, $this->fileService->getSourceFilesystem());
    }
}
