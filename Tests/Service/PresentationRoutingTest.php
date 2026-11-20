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
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

final class PresentationRoutingTest extends TestCase
{
    public static function manifestRoutes(): array
    {
        return [
            'German, shared route' => ['de', '/iiif/presentation/{id}/manifest'],
            'English, shared route' => ['en', '/iiif/presentation/{id}/manifest'],
            'German, localized route' => ['de', '/{_locale}/iiif/presentation/{id}/manifest'],
            'English, localized route' => ['en', '/{_locale}/iiif/presentation/{id}/manifest'],
            'No translator, legacy attribution' => ['de', '/iiif/presentation/{id}/manifest', false],
        ];
    }

    #[DataProvider('manifestRoutes')]
    public function testManifestPreservesRoutingParametersAcrossHostChanges(string $locale, string $manifestPath, bool $useTranslator = true): void
    {
        $routes = new RouteCollection();
        $routes->add('localized_page', new Route('/{_locale}/id/{id}', requirements: ['_locale' => 'de|en']));
        $routes->add('subugoe_iiif_manifest', new Route($manifestPath));
        $routes->add('subugoe_iiif_sequence', new Route('/iiif/presentation/{id}/sequence/{name}'));
        $routes->add('subugoe_iiif_canvas', new Route('/iiif/presentation/{id}/canvas/{canvas}'));
        $routes->add('subugoe_iiif_imagepresentation', new Route('/iiif/presentation/{id}/annotation/{name}'));
        $routes->add('subugoe_iiif_image_base', new Route('/iiif/image/{identifier}'));
        $routes->add('subugoe_iiif_image', new Route('/iiif/image/{identifier}/{region}/{size}/{rotation}/{quality}.{format}'));

        $context = new RequestContext('', 'GET', 'website.example.org', 'https');
        $parameters = ['_locale' => $locale, 'tenant' => 'library'];
        $context->setParameters($parameters);
        $router = new Router(new ClosureLoader(), static fn (): RouteCollection => $routes, [], $context);
        $translator = new Translator($locale);
        $translator->addLoader('yaml', new YamlFileLoader());
        $translator->addResource('yaml', __DIR__.'/../../Resources/translations/iiif.'.$locale.'.yaml', $locale, 'iiif');
        $service = new PresentationService(
            $router,
            ['thumbnail_size' => '96,', 'http' => ['scheme' => 'https', 'host' => 'images.example.org']],
            ['service_id' => 'https://website.example.org/', 'logo' => 'https://website.example.org/logo.svg', 'http' => ['scheme' => 'https', 'host' => 'manifests.example.org']],
            $useTranslator ? $translator : null,
        );

        $document = new Document();
        $document->setId('PPN1684414199');
        $document->setTitle(['Example']);
        $document->setMetadata([]);
        $document->setDescription('');
        $document->setRightsOwner(['Example Library']);
        $page = new PhysicalStructure();
        $page->setIdentifier('PPN1684414199_00000001');
        $page->setLabel('1');
        $document->addPhysicalStructure($page);

        $manifest = $service->getManifest($document);

        $manifestUrl = 'https://manifests.example.org'.strtr($manifestPath, ['{_locale}' => $locale, '{id}' => $document->getId()]);
        self::assertSame($manifestUrl, $manifest->getId());
        self::assertSame($locale === 'en' ? 'Provided by: Example Library' : 'Bereitgestellt von: Example Library', $manifest->getAttribution());
        self::assertSame('https://images.example.org/iiif/image/PPN1684414199_00000001/full/96,/0/default.jpg', $manifest->getThumbnail()->getId());
        $canvas = $manifest->getSequences()[0]->getCanvases()[0];
        self::assertSame('https://manifests.example.org/iiif/presentation/PPN1684414199/canvas/PPN1684414199_00000001', $canvas->getId());
        self::assertSame('https://images.example.org/iiif/image/PPN1684414199_00000001', $canvas->getImages()[0]->getResource()->getService()->getId());
        self::assertSame($parameters, $router->getContext()->getParameters());
        self::assertSame('/'.$locale.'/id/PPN1684414199', $router->generate('localized_page', ['id' => $document->getId()]));
        self::assertSame('website.example.org', $context->getHost());
    }
}
