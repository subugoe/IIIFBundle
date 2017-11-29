<?php

namespace Subugoe\IIIFBundle\Model;

use MyCLabs\Enum\Enum;

/**
 * @see http://dfg-viewer.de/strukturdatenset/
 * We only use a small subset as defined some specifications
 * on http://www.eromm.org/dcgkb/doku.php?id=goobi:subregelsatz:start
 */
class DocumentTypes extends Enum
{
    const SECTION = 'section';
    const ARTICLE = 'article';
    const ISSUE = 'issue';
    const VOLUME = 'volume';
    const CONTAINED_WORK = 'contained_work';
    const BINDING = 'binding';
    const CORRIGENDA = 'corrigenda';
    const ILLUSTRATION = 'illustration';
    const TABLE_OF_CONTENTS = 'contents';
    const MAP = 'map';
    const COLOPHON = 'colophon';
    const ENGRAVED_TITLEPAGE = 'engraved_titlepage';
    const MULTIVOLUME_WORK = 'multivolume_work';
    const MONOGRAPH = 'monograph';
    const MUSICAL_NOTATION = 'musical_notation';
    const PERIODICAL = 'periodical';
    const INDEX = 'index';
    const TABLE = 'table';
    const TITLE_PAGE = 'title_page';
    const PREFACE = 'preface';
    const DEDICATION = 'dedication';
    const FOLDER = 'folder';
    
    const UNKNOWN = 'unknown';
}
