<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\IIIFBundle\Model\Document;
use Subugoe\IIIFBundle\Model\Presentation\Collection;

interface TranslatorInterface
{
    /**
     * @param string $id
     *
     * @return Document
     */
    public function getDocumentById(string $id): Document;

    /**
     * @param string $imageId
     *
     * @return Document
     */
    public function getDocumentByImageId(string $imageId): Document;

    /**
     * @param string $collectionId
     *
     * @return Collection
     */
    public function getCollectionById(string $collectionId);
}
