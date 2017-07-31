<?php

namespace Subugoe\IIIFBundle\Tests;

use PHPUnit\Framework\TestCase;
use Subugoe\IIIFBundle\Model\Image\Image;
use Subugoe\IIIFBundle\Service\ImageService;

class ImageServiceTest extends TestCase
{
    /**
     * @var ImageService
     */
    protected $imageService;

    public function setUp()
    {
        $imageService = $this
            ->getMockBuilder(ImageService::class)
            ->disableOriginalConstructor()
            ->setMethods(['getImageHash'])
            ->getMock();

        $this->imageService = $imageService;
    }

    public function testIdentifierCalculation()
    {
        $image = (new Image())->setIdentifier('a');
        $image->setFormat('jpg');
        $identifier = $this->imageService->getCachedFileIdentifier($image);

        $this->assertSame('a/7ece429f5899ac0466fe0cb626a6f394e1c83fb2099cea96d0d9ad68a77ec3ec.jpg', $identifier);
    }
}
