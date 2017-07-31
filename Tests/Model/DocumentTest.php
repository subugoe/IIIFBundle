<?php

namespace tests\Subugoe\IIIFBundle\Model;

use PHPUnit\Framework\TestCase;
use Subugoe\IIIFBundle\Model\Document;

class DocumentTest extends TestCase
{
    /**
     * @var Document
     */
    private $document;

    public function setUp()
    {
        $this->document = new Document();
    }

    public function testSettingMetadataWithAnArrayReturnsArray()
    {
        $this->document->setMetadata([]);
        $this->assertSame($this->document->getMetadata(), []);
    }
}
