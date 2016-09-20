<?php
use App\Legacy\Product\Product;

class ProductTest extends TestCase
{
    /** @test */
    public function a_product_has_a_price()
    {
        $product = Product::find(2877);

        $this->assertEquals(461, $product->price);
    }
}
