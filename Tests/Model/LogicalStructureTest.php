<?php

namespace tests\Subugoe\IIIFBundle\Model;

use Subugoe\IIIFBundle\Model\LogicalStructure;

/**
 * Test for logical structure element.
 */
class LogicalStructureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LogicalStructure
     */
    private $logicalStructure;

    public function setUp()
    {
        $this->logicalStructure = new LogicalStructure();
    }

    public function testSettingTheIdSetsTheId()
    {
        $id = 'guzu';

        $this->logicalStructure->setId($id);
        $this->assertSame($id, $this->logicalStructure->getId());
    }

    public function testSettingTheLabelSetsTheLabel()
    {
        $label = 'guzu';

        $this->logicalStructure->setLabel($label);
        $this->assertSame($label, $this->logicalStructure->getLabel());
    }

    public function testSettingTheTypeReturnsTheType()
    {
        $type = 'guzu';

        $this->logicalStructure->setType($type);
        $this->assertSame($type, $this->logicalStructure->getType());
    }

    public function testStartPageSetsTheStartPage()
    {
        $startPage = 100;

        $this->logicalStructure->setStartPage($startPage);
        $this->assertSame($startPage, $this->logicalStructure->getStartPage());
    }

    public function testEndPageSetsTheStartPage()
    {
        $endPage = 100;

        $this->logicalStructure->setEndPage($endPage);
        $this->assertSame($endPage, $this->logicalStructure->getEndPage());
    }

    public function testSettingTheLevelSetsTheLabel()
    {
        $level = 100;

        $this->logicalStructure->setLevel($level);
        $this->assertSame($level, $this->logicalStructure->getLevel());
    }
}
