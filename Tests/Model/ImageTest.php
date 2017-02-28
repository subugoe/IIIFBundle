<?php

declare(strict_types=1);

namespace tests\AppBundle\Service;

use Subugoe\IIIFBundle\Model\Image\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Image
     */
    protected $fixture;

    public function setUp()
    {
        parent::setUp();
        $this->fixture = new Image();
    }

    public function identifierProvider()
    {
        return [
            [
                'PPN341721271',
                'PPN341721271/00000001',
            ],
            [
                'PPN341721271:00000001',
                'PPN341721271/00000001',
            ],
            [
                '1:1',
                '1/1',
            ],
        ];
    }

    public function rotationProvider()
    {
        return [
            [
                90,
                90,
            ],
            [
                '!90',
                '!90',
            ],
            [
                0,
                0,
            ],
        ];
    }

    /**
     * @dataProvider identifierProvider
     */
    public function testIdentifierHandlesAllUsedVariations($identifier, $expected)
    {
        $this->fixture->setIdentifier($identifier);
        $result = $this->fixture->getIdentifier();

        $this->assertSame($expected, $result);
    }

    public function testSettingTheRegionWillReturnTheCorrectRegion()
    {
        $label = 'Titel';
        $this->fixture->setRegion($label);

        $this->assertSame($label, $this->fixture->getRegion());
    }

    /**
     * @dataProvider rotationProvider
     */
    public function testSettingRotationWillReturnCorrectRotation($rotation, $expected)
    {
        $this->fixture->setRotation($rotation);
        $result = $this->fixture->getRotation();

        $this->assertSame($expected, $result);
    }
}
