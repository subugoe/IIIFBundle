<?php

namespace Subugoe\IIIFBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Subugoe\IIIFModel\Model\LogicalStructure;

/**
 * Test for logical structure element.
 */
class LogicalStructureTest extends TestCase
{
    private LogicalStructure $logicalStructure;

    protected function setUp(): void
    {
        $this->logicalStructure = new LogicalStructure();
    }

    public function testSettingTheIdSetsTheId(): void
    {
        $id = 'guzu';

        $this->logicalStructure->setId($id);
        $this->assertSame($id, $this->logicalStructure->getId());
    }

    public function testSettingTheLabelSetsTheLabel(): void
    {
        $label = 'guzu';

        $this->logicalStructure->setLabel($label);
        $this->assertSame($label, $this->logicalStructure->getLabel());
    }

    public function testSettingTheTypeReturnsTheType(): void
    {
        $type = 'guzu';

        $this->logicalStructure->setType($type);
        $this->assertSame($type, $this->logicalStructure->getType());
    }

    public function testStartPageSetsTheStartPage(): void
    {
        $startPage = 100;

        $this->logicalStructure->setStartPage($startPage);
        $this->assertSame($startPage, $this->logicalStructure->getStartPage());
    }

    public function testEndPageSetsTheStartPage(): void
    {
        $endPage = 100;

        $this->logicalStructure->setEndPage($endPage);
        $this->assertSame($endPage, $this->logicalStructure->getEndPage());
    }

    public function testSettingTheLevelSetsTheLabel(): void
    {
        $level = 100;

        $this->logicalStructure->setLevel($level);
        $this->assertSame($level, $this->logicalStructure->getLevel());
    }
}
