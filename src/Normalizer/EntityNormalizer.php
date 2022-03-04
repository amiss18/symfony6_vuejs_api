<?php

namespace App\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Fournit l'entité jointe à partir de son id
 * ex:
 */
class EntityNormalizer extends ObjectNormalizer {

    public function __construct(
        private EntityManagerInterface  $em,
        ?ClassMetadataFactoryInterface  $classMetadataFactory = null,
        ?NameConverterInterface         $nameConverter = null,
        ?PropertyAccessorInterface      $propertyAccessor = null,
        ?PropertyTypeExtractorInterface $propertyTypeExtractor = null
    ) {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null): bool {
        return strpos($type, 'App\\Entity\\') == 0 && (is_numeric($data) || is_string($data));
    }

    /**
     * fournit le subresource(category) lors de la dénormalisation du product
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = []) {
        return $this->em->find($class, $data);
    }

}