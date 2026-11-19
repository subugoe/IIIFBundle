<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Tests\Service;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Subugoe\IIIFBundle\Service\PresentationService;
use Subugoe\IIIFModel\Model\Document;
use Subugoe\IIIFModel\Model\PhysicalStructure;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

final class PresentationRoutingTest extends TestCase
{
    public static function locales(): array
    {
        return ['German' => ['de'], 'English' => ['en']];
    }

    #[DataProvider('locales')]
    public function testManifestPreservesRoutingParametersAcrossHostChanges(string $locale): void
    {
        $routes = new RouteCollection();
        $routes->add('_detail', new Route('/{_locale}/id/{id}', requirements: ['_locale' => 'de|en']));
        $routes->add('subugoe_iiif_manifest', new Route('/iiif/presentation/{id}/manifest'));
        $routes->add('subugoe_iiif_sequence', new Route('/iiif/presentation/{id}/sequence/{name}'));
        $routes->add('subugoe_iiif_canvas', new Route('/iiif/presentation/{id}/canvas/{canvas}'));
        $routes->add('subugoe_iiif_imagepresentation', new Route('/iiif/presentation/{id}/annotation/{name}'));
        $routes->add('subugoe_iiif_image_base', new Route('/iiif/image/{identifier}'));
        $routes->add('subugoe_iiif_image', new Route('/iiif/image/{identifier}/{region}/{size}/{rotation}/{quality}.{format}'));

        $context = new RequestContext('', 'GET', 'website.example.org', 'https');
        $parameters = ['_locale' => $locale, 'tenant' => 'library'];
        $context->setParameters($parameters);
        $router = new Router(new ClosureLoader(), static fn (): RouteCollection => $routes, [], $context);
        $service = new PresentationService(
            $router,
            ['thumbnail_size' => '96,', 'http' => ['scheme' => 'https', 'host' => 'images.example.org']],
            ['service_id' => 'https://website.example.org/', 'logo' => 'https://website.example.org/logo.svg', 'http' => ['scheme' => 'https', 'host' => 'manifests.example.org']],
        );

        $document = new Document();
        $document->setId('PPN1684414199');
        $document->setTitle(['Example']);
        $document->setMetadata([]);
        $document->setDescription('');
        $page = new PhysicalStructure();
        $page->setIdentifier('PPN1684414199_00000001');
        $page->setLabel('1');
        $document->addPhysicalStructure($page);

        $manifest = $service->getManifest($document);

        self::assertSame('https://manifests.example.org/'.$locale.'/id/PPN1684414199', $manifest->getId());
        self::assertSame('https://images.example.org/iiif/image/PPN1684414199_00000001/full/96,/0/default.jpg', $manifest->getThumbnail()->getId());
        $canvas = $manifest->getSequences()[0]->getCanvases()[0];
        self::assertSame('https://manifests.example.org/iiif/presentation/PPN1684414199/canvas/PPN1684414199_00000001', $canvas->getId());
        self::assertSame('https://images.example.org/iiif/image/PPN1684414199_00000001', $canvas->getImages()[0]->getResource()->getService()->getId());
        self::assertSame($parameters, $router->getContext()->getParameters());
        self::assertSame('website.example.org', $context->getHost());
    }
}
