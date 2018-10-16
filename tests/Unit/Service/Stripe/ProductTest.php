<?php

namespace Tests\Unit\Service\Stripe;

use Carbon\Carbon;
use App\Service\Stripe\Sku;
use App\Service\Stripe\Images;
use PHPUnit\Framework\TestCase;
use App\Service\Stripe\Product;
use App\Service\Stripe\PackageDimensions;

class ProductTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_simple_product()
    {
        $product = new Product('Product Name');

        $this->assertSame([
            'active' => true,
            'name' => 'Product Name',
            'type' => 'good',
        ], $product->toArray());
    }

    /**
     * @test
     */
    public function can_add_additional_data()
    {
        $product = new Product('Product Name');
        $product->setName('New Product Name');
        $product->setActive(false);
        $product->setCaption('Some caption');
        $product->setDescription('Some description');
        $product->setImages(
            (new Images())
                ->add('http://example.com/1.jpg')
                ->add('http://example.com/2.jpg')
        );
        $product->setPackageDimensions(
            new PackageDimensions(
                2,
                3,
                4,
                10
            )
        );
        $product->setUrl('http://example.com/1');

        $this->assertSame([
            'active' => false,
            'name' => 'New Product Name',
            'type' => 'good',
            'caption' => 'Some caption',
            'description' => 'Some description',
            'images' => [
                'http://example.com/1.jpg',
                'http://example.com/2.jpg',
            ],
            'package_dimensions' => [
                'height' => 2.0,
                'width' => 3.0,
                'length' => 4.0,
                'weight' => 10.0,
            ],
            'url' => 'http://example.com/1',
        ], $product->toArray());
    }

    /**
     * @test
     */
    public function can_set_auto_generated_data()
    {
        $product = new Product('Product Name');
        $product->setId('prod_abcd');
        $product->setCreated($created = Carbon::now());

        $this->assertSame([
            'id' => 'prod_abcd',
            'active' => true,
            'name' => 'Product Name',
            'type' => 'good',
            'created' => (int) $created->format('U'),
        ], $product->toArray());
    }

    /**
     * @test
     */
    public function can_set_sku()
    {
        $product = new Product('Product Name');
        $product->setId('prod_abcd');
        $product->setSku(new Sku('proj_abcd', 10000, 'dkk', 1));

        $this->assertSame([
            'id' => 'prod_abcd',
            'active' => true,
            'name' => 'Product Name',
            'type' => 'good',
            'sku' => [
                'product' => 'proj_abcd',
                'active' => true,
                'price' => 10000,
                'currency' => 'dkk',
                'inventory' => [
                    'type' => 'finite',
                    'quantity' => 1,
                ],
            ],
        ], $product->toArray());
    }
}
