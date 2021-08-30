<?php

namespace Subugoe\IIIFBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Subugoe\IIIFModel\Model\Document;

class DocumentTest extends TestCase
{
    /**
     * @var Document
     */
    private $document;

    protected function setUp(): void
    {
        $this->document = new Document();
    }

    public function testSettingMetadataWithAnArrayReturnsArray()
    {
        $this->document->setMetadata([]);
        $this->assertSame($this->document->getMetadata(), []);
    }
}
