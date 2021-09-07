<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\IIIFModel\Model\DocumentInterface;
use Subugoe\IIIFModel\Model\Presentation\Collection;

interface TranslatorInterface
{
    public function getDocumentById(string $id): DocumentInterface;

    public function getDocumentBy(string $field, string $value, array $fields = []): DocumentInterface;

    public function getDocumentByImageId(string $imageId): DocumentInterface;

    public function getCollectionById(string $collectionId);
}
