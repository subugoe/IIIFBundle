<?php

namespace Subugoe\IIIFBundle\Tests\Translator;

use Subugoe\IIIFBundle\Model\Document;
use Subugoe\IIIFBundle\Model\DocumentTypes;
use Subugoe\IIIFBundle\Model\LogicalStructure;
use Subugoe\IIIFBundle\Model\PhysicalStructure;
use Subugoe\IIIFBundle\Translator\TranslatorInterface;

/**
 * For testing purposes.
 */
class TranslatorMock implements TranslatorInterface
{
    private $documents = [
        'PPN613131266' => [
                'image_format' => 'jpg',
                'iswork' => true,
                'context' => 'gdz',
                'doctype' => 'work',
                'identifier' => [
                    'vd17 VD17 7:705635K',
                    'PPNanalog PPN581166787',
                    'gbv-ppn PPN613131266',
                ],
                'id' => 'PPN613131266',
                'access_pattern' => 'gdz',
                'baseurl' => 'http://gdz-srv1.sub.uni-goettingen.de',
                'title' => ['Disputatio Theologica De Ministerio Ecclesiastico'],
                'sorttitle' => ['Disputatio Theologica De Ministerio Ecclesiastico'],
                'subtitle' => [' '],
                'bytitle' => 'Disputatio Theologica De Ministerio Ecclesiastico',
                'creator' => [
                    'Kortholt, Christianus',
                    'Bilsius, Johannes',
                ],
                'creator_type' => [
                    'personal',
                    'personal',
                ],
                'creator_gndURI' => [
                    ' ',
                    ' ',
                ],
                'creator_gndNumber' => [
                    ' ',
                    ' ',
                ],
                'creator_roleterm' => [
                    'aut',
                    'aut',
                ],
                'creator_roleterm_authority' => [
                    'marcrelator',
                    'marcrelator',
                ],
                'bycreator' => 'Kortholt, Christianus; Bilsius, Johannes',
                'facet_creator_personal' => [
                    'Kortholt, Christianus',
                    'Bilsius, Johannes',
                ],
                'genre' => ['Dissertation:theol.'],
                'dc' => ['VD17-nova'],
                'facet_dc' => ['VD17-nova'],
                'dc_authority' => ['ZVDD'],
                'place_publish' => ['Kiloni[i]'],
                'facet_place_publish' => ['Kiloni[i]'],
                'year_publish_string' => '1676',
                'year_publish' => 1676,
                'year_publish_start' => 1676,
                'publisher' => ['Reumannus'],
                'facet_publisher' => ['Reumannus'],
                'lang' => ['["lat", "Latin", "latin"]'],
                'product' => 'gdz',
                'facet_product' => ['gdz'],
                'work' => 'PPN613131266',
                'page' => [
                    '00000001',
                    '00000002',
                    '00000003',
                    '00000004',
                    '00000005',
                    '00000006',
                    '00000007',
                    '00000008',
                    '00000009',
                    '00000010',
                    '00000011',
                    '00000012',
                    '00000013',
                    '00000014',
                    '00000015',
                    '00000016',
                    '00000017',
                    '00000018',
                    '00000019',
                    '00000020',
                    '00000021',
                    '00000022',
                    '00000023',
                    '00000024',
                    '00000025',
                    '00000026',
                    '00000027',
                    '00000028',
                    '00000029',
                    '00000030',
                    '00000031',
                    '00000032',
                    '00000033',
                    '00000034',
                    '00000035',
                    '00000036',
                    '00000037',
                    '00000038',
                    '00000039',
                    '00000040',
                    '00000041',
                ],
                'mets_path' => 'mets/gdz/PPN613131266.mets.xml',
                'docstrct' => 'monograph',
                'log_id' => [
                    'LOG_0001',
                    'LOG_0002',
                    'LOG_0003',
                    'LOG_0004',
                    'LOG_0005',
                ],
                'log_type' => [
                    'title_page',
                    'dedication_foreword_intro',
                    'chapter',
                    'chapter',
                    'chapter',
                ],
                'log_label' => [
                    ' ',
                    ' ',
                    'Cap. I. De Causis',
                    'Cap. II. De Officio Ministrorum',
                    'Cap. III. De Adiunctis',
                ],
                'log_start_page_index' => [
                    1,
                    2,
                    5,
                    32,
                    39,
                ],
                'log_end_page_index' => [
                    1,
                    4,
                    31,
                    39,
                    41,
                ],
                'log_level' => [
                    1,
                    1,
                    1,
                    1,
                    1,
                ],
                'phys_order' => [
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13,
                    14,
                    15,
                    16,
                    17,
                    18,
                    19,
                    20,
                    21,
                    22,
                    23,
                    24,
                    25,
                    26,
                    27,
                    28,
                    29,
                    30,
                    31,
                    32,
                    33,
                    34,
                    35,
                    36,
                    37,
                    38,
                    39,
                    40,
                    41,
                ],
                'phys_orderlabel' => [
                    ' ',
                    ' ',
                    ' ',
                    ' ',
                    '1',
                    '2',
                    '3',
                    '4',
                    '5',
                    '6',
                    '7',
                    '8',
                    '9',
                    '10',
                    '11',
                    '12',
                    '13',
                    '14',
                    '15',
                    '16',
                    '17',
                    '18',
                    '19',
                    '20',
                    '21',
                    '22',
                    '23',
                    '24',
                    '25',
                    '26',
                    '27',
                    '28',
                    '29',
                    '30',
                    '31',
                    '32',
                    '33',
                    '34',
                    '35',
                    '36',
                    '37',
                ],
                'phys_first_page_index' => 1,
                'phys_last_page_index' => 41,
                'page_key' => [
                    'gdz:PPN613131266:00000001',
                    'gdz:PPN613131266:00000002',
                    'gdz:PPN613131266:00000003',
                    'gdz:PPN613131266:00000004',
                    'gdz:PPN613131266:00000005',
                    'gdz:PPN613131266:00000006',
                    'gdz:PPN613131266:00000007',
                    'gdz:PPN613131266:00000008',
                    'gdz:PPN613131266:00000009',
                    'gdz:PPN613131266:00000010',
                    'gdz:PPN613131266:00000011',
                    'gdz:PPN613131266:00000012',
                    'gdz:PPN613131266:00000013',
                    'gdz:PPN613131266:00000014',
                    'gdz:PPN613131266:00000015',
                    'gdz:PPN613131266:00000016',
                    'gdz:PPN613131266:00000017',
                    'gdz:PPN613131266:00000018',
                    'gdz:PPN613131266:00000019',
                    'gdz:PPN613131266:00000020',
                    'gdz:PPN613131266:00000021',
                    'gdz:PPN613131266:00000022',
                    'gdz:PPN613131266:00000023',
                    'gdz:PPN613131266:00000024',
                    'gdz:PPN613131266:00000025',
                    'gdz:PPN613131266:00000026',
                    'gdz:PPN613131266:00000027',
                    'gdz:PPN613131266:00000028',
                    'gdz:PPN613131266:00000029',
                    'gdz:PPN613131266:00000030',
                    'gdz:PPN613131266:00000031',
                    'gdz:PPN613131266:00000032',
                    'gdz:PPN613131266:00000033',
                    'gdz:PPN613131266:00000034',
                    'gdz:PPN613131266:00000035',
                    'gdz:PPN613131266:00000036',
                    'gdz:PPN613131266:00000037',
                    'gdz:PPN613131266:00000038',
                    'gdz:PPN613131266:00000039',
                    'gdz:PPN613131266:00000040',
                    'gdz:PPN613131266:00000041',
                ],
                'phys_desc_extent' => ['[2] Bl., 36 S'],
                'rights_owner' => ['Digitalisierungszentrum der Niedersächsischen Staats- und Universitätsbibliothek Göttingen'],
                'rights_owner_site_url' => ['http://gdz.sub.uni-goettingen.de'],
                'relateditem_id' => [' '],
                'relateditem_title' => ['VD17-nova'],
                'relateditem_title_abbreviated' => [' '],
                'relateditem_title_partnumber' => [' '],
                'relateditem_note' => [' '],
                'relateditem_type' => ['series'],
                'mods' => '<mods:mods xmlns:mods="http://www.loc.gov/mods/v3"><mods:classification authority="ZVDD">VD17-nova</mods:classification><mods:recordInfo><mods:recordIdentifier source="gbv-ppn">PPN613131266</mods:recordIdentifier></mods:recordInfo><mods:identifier type="vd17">VD17 7:705635K</mods:identifier><mods:identifier type="PPNanalog">PPN581166787</mods:identifier><mods:titleInfo><mods:title>Disputatio Theologica De Ministerio Ecclesiastico</mods:title></mods:titleInfo><mods:genre authority="vd17" type="class">Dissertation:theol.</mods:genre><mods:language><mods:languageTerm authority="iso639-2b" type="code">la</mods:languageTerm></mods:language><mods:originInfo><mods:place><mods:placeTerm type="text">Kiloni[i]</mods:placeTerm></mods:place><mods:dateIssued encoding="w3cdtf" keyDate="yes">1676</mods:dateIssued><mods:publisher>Reumannus</mods:publisher></mods:originInfo><mods:relatedItem type="series"><mods:titleInfo><mods:title>VD17-nova</mods:title></mods:titleInfo></mods:relatedItem><mods:name type="personal"><mods:role><mods:roleTerm authority="marcrelator" type="code">aut</mods:roleTerm></mods:role><mods:namePart type="family">Kortholt</mods:namePart><mods:namePart type="given">Christianus</mods:namePart><mods:displayForm>Kortholt, Christianus</mods:displayForm></mods:name><mods:name type="personal"><mods:role><mods:roleTerm authority="marcrelator" type="code">aut</mods:roleTerm></mods:role><mods:namePart type="family">Bilsius</mods:namePart><mods:namePart type="given">Johannes</mods:namePart><mods:displayForm>Bilsius, Johannes</mods:displayForm></mods:name><mods:physicalDescription><mods:extent>[2] Bl., 36 S</mods:extent></mods:physicalDescription></mods:mods>',
                '_version_' => 1562841272364826624,
                'uid' => '0bea9a4a-8f9e-4f32-93f8-8cc102ad6285',
                'date_modified' => '2017-03-25T11:33:02.073Z',
                'date_indexed' => '2017-03-25T11:33:02.073Z',
            ],
        'PPN629651310' => [
                'image_format' => 'jpg',
                'iswork' => true,
                'context' => 'gdz',
                'doctype' => 'work',
                'identifier' => [
                    'vd17 VD17 7:709428G',
                    'PPNanalog PPN602255465',
                    'gbv-ppn PPN629651310',
                ],
                'id' => 'PPN629651310',
                'access_pattern' => 'gdz',
                'baseurl' => 'http://gdz-srv1.sub.uni-goettingen.de',
                'title' => ['Von Gottes Gnaden/ Ernst Augustus/ Bischoff zu Oßnabrück/ Hertzog zu Braunschweig und Lüneburg/ [et]c. Nachdem Wir zwar hiebevor wegen des von Unser Milice/ auch andern Unsern Bedienten und Angehörigen unternehmenden ohngebürlichen Jagens und Schiessens gewisses Verbot und ernstliche Verordnung/ wie solche allhier inseriret/ ergehen lassen; Von Gottes Gnaden/ Wir Ernst Augustus/ Bischoff zu Oßnabrück/ Hertzog zu Braunschweig und Lüneburg/ [et]c. Fügen hiemit zu wissen/ ... daß ... verschiedene von Unser Milice ... in hiesiger Unser Residentz-Stadt ... zu Jagen und Schiessen sich unterstanden ...'],
                'sorttitle' => ['Von Gottes Gnaden/ Ernst Augustus/ Bischoff zu Oßnabrück/ Hertzog zu Braunschweig und Lüneburg/ [et]c. Nachdem Wir zwar hiebevor wegen des von Unser Milice/ auch andern Unsern Bedienten und Angehörigen unternehmenden ohngebürlichen Jagens und Schiessens gewisses Verbot und ernstliche Verordnung/ wie solche allhier inseriret/ ergehen lassen; Von Gottes Gnaden/ Wir Ernst Augustus/ Bischoff zu Oßnabrück/ Hertzog zu Braunschweig und Lüneburg/ [et]c. Fügen hiemit zu wissen/ ... daß ... verschiedene von Unser Milice ... in hiesiger Unser Residentz-Stadt ... zu Jagen und Schiessen sich unterstanden ...'],
                'subtitle' => ['Geben in Unser Residentz-Stadt Hannover den 8. Augusti/ 1691'],
                'bytitle' => 'Von Gottes Gnaden/ Ernst Augustus/ Bischoff zu Oßnabrück/ Hertzog zu Braunschweig und Lüneburg/ [et]c. Nachdem Wir zwar hiebevor wegen des von Unser Milice/ auch andern Unsern Bedienten und Angehörigen unternehmenden ohngebürlichen Jagens und Schiessens gewisses Verbot und ernstliche Verordnung/ wie solche allhier inseriret/ ergehen lassen; Von Gottes Gnaden/ Wir Ernst Augustus/ Bischoff zu Oßnabrück/ Hertzog zu Braunschweig und Lüneburg/ [et]c. Fügen hiemit zu wissen/ ... daß ... verschiedene von Unser Milice ... in hiesiger Unser Residentz-Stadt ... zu Jagen und Schiessen sich unterstanden ...',
                'genre' => [
                    'Verordnung',
                    'Einblattdruck',
                    'Amtsdruckschrift  / Gesetzessammlung  / Verordnung  / Vertrag  / Gesetz  / Edikt',
                ],
                'dc' => ['VD17-nova'],
                'facet_dc' => ['VD17-nova'],
                'dc_authority' => ['ZVDD'],
                'place_publish' => ['[S.l.]'],
                'facet_place_publish' => ['[S.l.]'],
                'year_publish_string' => '1691',
                'year_publish' => 1691,
                'year_publish_start' => 1691,
                'lang' => ['["ger", "German", "allemand"]'],
                'product' => 'gdz',
                'facet_product' => ['gdz'],
                'work' => 'PPN629651310',
                'page' => [
                    '00000001',
                    '00000002',
                ],
                'mets_path' => 'mets/gdz/PPN629651310.mets.xml',
                'docstrct' => 'monograph',
                'phys_order' => [
                    1,
                    2,
                ],
                'phys_orderlabel' => [
                    ' ',
                    ' ',
                ],
                'phys_first_page_index' => 1,
                'phys_last_page_index' => 2,
                'page_key' => [
                    'gdz:PPN629651310:00000001',
                    'gdz:PPN629651310:00000002',
                ],
                'phys_desc_extent' => ['[1] Bl'],
                'subject_type' => ['topic'],
                'subject' => ['von_gogne'],
                'facet_subject_topic' => ['von_gogne'],
                'rights_owner' => ['Digitalisierungszentrum der Niedersächsischen Staats- und Universitätsbibliothek Göttingen'],
                'rights_owner_site_url' => ['http://gdz.sub.uni-goettingen.de'],
                'relateditem_id' => [' '],
                'relateditem_title' => ['VD17-nova'],
                'relateditem_title_abbreviated' => [' '],
                'relateditem_title_partnumber' => [' '],
                'relateditem_note' => [' '],
                'relateditem_type' => ['series'],
                'mods' => '<mods:mods xmlns:mods="http://www.loc.gov/mods/v3"><mods:classification authority="ZVDD">VD17-nova</mods:classification><mods:recordInfo><mods:recordIdentifier source="gbv-ppn">PPN629651310</mods:recordIdentifier></mods:recordInfo><mods:identifier type="vd17">VD17 7:709428G</mods:identifier><mods:identifier type="PPNanalog">PPN602255465</mods:identifier><mods:titleInfo><mods:title>Von Gottes Gnaden/ Ernst Augustus/ Bischoff zu Oßnabrück/ Hertzog zu Braunschweig und Lüneburg/ [et]c. Nachdem Wir zwar hiebevor wegen des von Unser Milice/ auch andern Unsern Bedienten und Angehörigen unternehmenden ohngebürlichen Jagens und Schiessens gewisses Verbot und ernstliche Verordnung/ wie solche allhier inseriret/ ergehen lassen; Von Gottes Gnaden/ Wir Ernst Augustus/ Bischoff zu Oßnabrück/ Hertzog zu Braunschweig und Lüneburg/ [et]c. Fügen hiemit zu wissen/ ... daß ... verschiedene von Unser Milice ... in hiesiger Unser Residentz-Stadt ... zu Jagen und Schiessen sich unterstanden ...</mods:title><mods:subTitle>Geben in Unser Residentz-Stadt Hannover den 8. Augusti/ 1691</mods:subTitle></mods:titleInfo><mods:genre authority="vd17" type="class">Verordnung</mods:genre><mods:genre authority="vd17" type="class">Einblattdruck</mods:genre><mods:genre authority="vd17" type="class">Amtsdruckschrift  / Gesetzessammlung  / Verordnung  / Vertrag  / Gesetz  / Edikt</mods:genre><mods:language><mods:languageTerm authority="iso639-2b" type="code">de</mods:languageTerm></mods:language><mods:originInfo><mods:place><mods:placeTerm type="text">[S.l.]</mods:placeTerm></mods:place><mods:dateIssued encoding="w3cdtf" keyDate="yes">1691</mods:dateIssued></mods:originInfo><mods:subject authority="gdz"><mods:topic>von_gogne</mods:topic></mods:subject><mods:relatedItem type="series"><mods:titleInfo><mods:title>VD17-nova</mods:title></mods:titleInfo></mods:relatedItem><mods:physicalDescription><mods:extent>[1] Bl</mods:extent></mods:physicalDescription><mods:extension><zvdd:zvddWrap xmlns:zvdd="http://zvdd.gdz-cms.de/"><zvdd:titleWord>Bischof Osnabrück Herzog Miliz Residenzstadt</zvdd:titleWord></zvdd:zvddWrap></mods:extension></mods:mods>',
                '_version_' => 1562836823366959104,
                'uid' => '48c2e3d6-a1ae-425e-908c-6f1729b34fc3',
                'date_modified' => '2017-03-25T10:22:19.179Z',
                'date_indexed' => '2017-03-25T10:22:19.179Z',
            ],
    ];

    /**
     * @param string $id
     *
     * @return Document
     */
    public function getDocumentById(string $id): Document
    {
        $document = new Document();
        $solrDocument = $this->documents[$id];
        $numberOfLogicalStructures = isset($solrDocument['log_id']) ? count($solrDocument['log_id']) : 0;
        $numberOfPhysicalStructures = count($solrDocument['phys_order']);

        $document
            ->setId($id)
            ->setType($this->getMappedDocumentType($solrDocument['doctype']))
            ->setRightsOwner($solrDocument['rights_owner'] ?: [])
            ->setTitle($solrDocument['title'])
            ->setAuthors(isset($solrDocument['creator']) ? $solrDocument['creator'] : [])
            ->setPublishingPlaces($solrDocument['place_publish'] ?: [])
            ->setClassification($solrDocument['dc'])
            ->setPublishingYear((int) $solrDocument['year_publish'] ?: 0)
            ->setPublisher(isset($solrDocument['publisher']) ? $solrDocument['publisher'] : [])
            ->setLanguage($solrDocument['lang'] ?: [])
            ->setImageFormat($solrDocument['image_format'])
            ->setDescription('');

        for ($i = 0; $i < $numberOfLogicalStructures; ++$i) {
            $structure = new LogicalStructure();

            $structure
                ->setId($solrDocument['log_id'][$i])
                ->setLabel($solrDocument['log_label'][$i])
                ->setType($solrDocument['log_type'][$i])
                ->setLevel((int) $solrDocument['log_level'][$i])
                ->setStartPage($solrDocument['log_start_page_index'][$i])
                ->setEndPage($solrDocument['log_end_page_index'][$i]);

            $document->addLogicalStructure($structure);
        }

        for ($i = 0; $i < $numberOfPhysicalStructures; ++$i) {
            $structure = new PhysicalStructure();
            $structure
                ->setIdentifier($solrDocument['page_key'][$i])
                ->setLabel($solrDocument['phys_orderlabel'][$i])
                ->setOrder($solrDocument['phys_order'][$i])
                ->setPage($solrDocument['page'][$i])
                ->setFilename(vsprintf(
                    '%s/%s.%s', [
                        $solrDocument['id'],
                        $solrDocument['page'][$i],
                        'tif',
                    ]
                ));

            $document->addPhysicalStructure($structure);
        }

        if (isset($solrDocument['parentdoc_work'])) {
            $numberOfParentDocuments = count($solrDocument['parentdoc_work']);
            for ($i = 0; $i < $numberOfParentDocuments; ++$i) {
                $parent = new Document();
                $parent
                    ->setId($solrDocument['parentdoc_work'][$i])
                    ->setType($this->getMappedDocumentType($solrDocument['parentdoc_type'][$i]))
                    ->setTitle([$solrDocument['parentdoc_label'][$i]]);

                $document->setParent($parent);
            }
        }

        return $document;
    }

    /**
     * @param string $doctype
     *
     * @return string
     */
    private function getMappedDocumentType(string $doctype)
    {
        $typeMapping = [
            'monograph' => DocumentTypes::MONOGRAPH,
            'periodicalvolume' => DocumentTypes::ISSUE,
            'volume' => DocumentTypes::VOLUME,
            'periodical' => DocumentTypes::PERIODICAL,
            'multivolume_work' => DocumentTypes::MULTIVOLUME_WORK,
            'multivolumework' => DocumentTypes::MULTIVOLUME_WORK,
            'folder' => DocumentTypes::MULTIVOLUME_WORK,
            'manuscript' => DocumentTypes::MONOGRAPH,
        ];

        if (array_key_exists($doctype, $typeMapping)) {
            return $typeMapping[$doctype];
        }

        return DocumentTypes::UNKNOWN;
    }

    public function getDocumentByImageId(string $imageId): Document
    {
        return $this->getDocumentById($this->documents[0]);
    }
}
