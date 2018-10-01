<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\IIIFBundle\Model\DocumentInterface;
use Subugoe\IIIFBundle\Model\Presentation\Collection;

interface TranslatorInterface
{
    /**
     * @param string $id
     *
     * @return DocumentInterface
     */
    public function getDocumentById(string $id): DocumentInterface;

    /**
     * @param string $field
     * @param string $value
     * @param array  $fields
     *
     * @return DocumentInterface
     */
    public function getDocumentBy(string $field, string $value, array $fields = []): DocumentInterface;

    /**
     * @param string $imageId
     *
     * @return DocumentInterface
     */
    public function getDocumentByImageId(string $imageId): DocumentInterface;

    /**
     * @param string $collectionId
     *
     * @return Collection
     */
    public function getCollectionById(string $collectionId);
}
