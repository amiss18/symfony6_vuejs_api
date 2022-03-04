<?php

namespace App\Service;

use App\Entity\Product;

class ProductManager implements ProductManagerInterface {



    /**
     * @inheritDoc
     */
    public function UpdateProduct(Product $product, Product $newProduct): Product {
        return $product->setName( $newProduct->getName())
            ->setDescription($newProduct->getDescription())
            ->setPrice( $newProduct->getPrice())
            ->setCategory( $newProduct->getCategory())
            ;
    }
}