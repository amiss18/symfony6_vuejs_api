<?php

namespace App\Service;

use App\Entity\Product;

interface ProductManagerInterface {



    /**
     * @param Product $product
     * @param Product $newProduct
     * @return Product
     */
    public function UpdateProduct( Product $product, Product $newProduct ):Product;

}