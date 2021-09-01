<?php

namespace Subugoe\IIIFBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Subugoe\IIIFModel\Model\Document;

class DocumentTest extends TestCase
{
    private Document $document;

    protected function setUp(): void
    {
        $this->document = new Document();
    }

    public function testSettingMetadataWithAnArrayReturnsArray(): void
    {
        $this->document->setMetadata([]);
        $this->assertSame($this->document->getMetadata(), []);
    }
}
