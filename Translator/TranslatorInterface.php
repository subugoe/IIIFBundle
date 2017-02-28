<?php

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\IIIFBundle\Model\Document;

interface TranslatorInterface
{
    public function getDocumentById(string $id): Document;
}
