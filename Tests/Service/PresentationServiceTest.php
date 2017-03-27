<?php

namespace Subugoe\IIIFBundle\Tests;

use Subugoe\IIIFBundle\Service\PresentationService;
use Subugoe\IIIFBundle\Tests\Translator\TranslatorMock;
use Subugoe\IIIFBundle\Translator\TranslatorInterface;
use Symfony\Component\Routing\Router;
use PHPUnit\Framework\TestCase;

class PresentationServiceTest extends TestCase
{
    /**
     * @var PresentationService
     */
    private $presentationService;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function setUp()
    {
        $router = $this->getRouterMock();
        $this->translator = new TranslatorMock();

        $imageConfiguration = [
            'thumbnail_size' => '556',
        ];

        $presentationConfiguration = [
            'service_id' => 'gdz',
            'logo' => 'gdz',
        ];

        $this->presentationService = new PresentationService($router, $imageConfiguration, $presentationConfiguration);
    }

    public function documentProvider()
    {
        return [
            ['PPN613131266'],
            ['PPN629651310'],
        ];
    }

    public function RangeProvider()
    {
        return [
            ['PPN613131266', 27],
        ];
    }

    /**
     * @dataProvider documentProvider
     */
    public function testSequences($id)
    {
        $document = $this->translator->getDocumentById($id);
        $this->presentationService->getManifest($document);
    }

    /**
     * @dataProvider rangeProvider
     */
    public function testRanges($id, $count)
    {
        $document = $this->translator->getDocumentById($id);
        $ranges = $this->presentationService->getRange($document, 'LOG_0003');

        $this->assertSame($count, count($ranges->getMembers()));
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected function getRouterMock()
    {
        $mock = $this->getMockBuilder(Router::class)
                     ->disableOriginalConstructor()
                     ->setMethods(['generate', 'supports', 'exists'])
                     ->getMockForAbstractClass();

        $mock->expects($this->any())
             ->method('generate')
             ->will($this->returnValue('https://gdz.sub.uni-goettingen.de'));

        return $mock;
    }
}
