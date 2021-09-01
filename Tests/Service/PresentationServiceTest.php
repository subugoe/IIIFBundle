<?php

namespace Subugoe\IIIFBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Subugoe\IIIFBundle\Exception\MalformedDocumentException;
use Subugoe\IIIFBundle\Service\PresentationService;
use Subugoe\IIIFBundle\Tests\Translator\TranslatorMock;
use Subugoe\IIIFBundle\Translator\TranslatorInterface;
use Symfony\Component\Routing\Router;

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

    protected function setUp(): void
    {
        $router = $this->getRouterMock();
        $this->translator = new TranslatorMock();

        $imageConfiguration = [
            'thumbnail_size' => '556',
            'http' => [
                'scheme' => 'https',
                'host' => 'example.com',
            ],
        ];

        $presentationConfiguration = [
            'service_id' => 'gdz',
            'logo' => 'gdz',
            'http' => [
                'scheme' => 'https',
                'host' => 'example.com',
            ],
        ];

        $this->presentationService = new PresentationService($router, $imageConfiguration, $presentationConfiguration);
    }

    public function documentProvider(): array
    {
        return [
            ['PPN613131266'],
            ['PPN629651310'],
            ['PPN592283860'],
        ];
    }

    public function annotationProvider(): array
    {
        return [
            ['PPN613131266', false],
            ['PPN629651310', false],
            ['PPN592283860', true],
            ['PPN599471603_0013', false],
        ];
    }

    public function malformedDocumentProvider(): array
    {
        return [
            ['PPN599471603_0013'],
            ['PPN530582384'],
            ['PPN617021074'],
        ];
    }

    public function RangeProvider(): array
    {
        return [
            ['PPN613131266', 27],
        ];
    }

    /**
     * @dataProvider documentProvider
     */
    public function testSequences($id): void
    {
        $document = $this->translator->getDocumentById($id);
        $this->presentationService->getManifest($document);
    }

    /**
     * @dataProvider rangeProvider
     */
    public function testRanges($id, $count): void
    {
        $document = $this->translator->getDocumentById($id);
        $ranges = $this->presentationService->getRange($document, 'LOG_0003');

        $this->assertSame($count, count($ranges->getMembers()));
    }

    /**
     * @dataProvider annotationProvider
     */
    public function testAnnotationExistance($id, $expected): void
    {
        $document = $this->translator->getDocumentById($id);
        $this->assertSame(!empty($document->getPhysicalStructure(0)->getAnnotation()), $expected);
    }

    /**
     * @dataProvider malformedDocumentProvider
     */
    public function testMalformedDocument($id): void
    {
        $this->expectException(MalformedDocumentException::class);
        $document = $this->translator->getDocumentById($id);
        $this->presentationService->getManifest($document);
    }

    protected function getRouterMock()
    {
        $mock = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->addMethods(['supports', 'exists'])
            ->onlyMethods(['generate'])
            ->getMockForAbstractClass();

        $mock->expects($this->any())
            ->method('generate')
            ->willReturn('https://gdz.sub.uni-goettingen.de');

        return $mock;
    }
}
