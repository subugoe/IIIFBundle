<?php

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\IIIFBundle\Model\Document;

interface TranslatorInterface
{
    /**
     * @param string $id
     *
     * @return Document
     */
    public function getDocumentById(string $id): Document;
}
