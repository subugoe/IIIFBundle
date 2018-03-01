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
    const ARTICLE = 'article';
    const BINDING = 'binding';
    const BUNDLE = 'bundle';
    const COLOPHON = 'colophon';
    const CONTAINED_WORK = 'contained_work';
    const CORRIGENDA = 'corrigenda';
    const DEDICATION = 'dedication';
    const ENGRAVED_TITLEPAGE = 'engraved_titlepage';
    const FILE = 'file';
    const FOLDER = 'folder';
    const ILLUSTRATION = 'illustration';
    const INDEX = 'index';
    const ISSUE = 'issue';
    const MAP = 'map';
    const MONOGRAPH = 'monograph';
    const MULTIVOLUME_WORK = 'multivolume_work';
    const MUSICAL_NOTATION = 'musical_notation';
    const PERIODICAL = 'volume';
    const PREFACE = 'preface';
    const SECTION = 'section';
    const TABLE_OF_CONTENTS = 'contents';
    const TABLE = 'table';
    const TITLE_PAGE = 'title_page';
    const VOLUME = 'volume';

    const UNKNOWN = 'unknown';
}
