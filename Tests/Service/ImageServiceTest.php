<?php

namespace Subugoe\IIIFBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Subugoe\IIIFModel\Model\Image\Image;
use Subugoe\IIIFBundle\Service\ImageService;

class ImageServiceTest extends TestCase
{
    /**
     * @var ImageService
     */
    protected $imageService;

    protected function setUp(): void
    {
        $imageService = $this
            ->getMockBuilder(ImageService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getImageHash'])
            ->getMock();

        $this->imageService = $imageService;
    }

    public function testIdentifierCalculation()
    {
        $image = (new Image())->setIdentifier('a');
        $image->setFormat('jpg');
        $identifier = $this->imageService->getCachedFileIdentifier($image);

        $this->assertSame('a/9bbf4fcfccd6f56f1c01728cacee96e01da5379855d85da9a19d759a79349a49.jpg', $identifier);
    }
}
