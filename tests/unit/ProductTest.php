<?php

use App\Legacy\Product\Product;

class ProductTest extends PHPUnit_Framework_TestCase
{
    public function testAProductHasNoTimestamps()
    {
        $product = Product;
        $this->assertEquals(false, $product->timestamps);
    }
}
