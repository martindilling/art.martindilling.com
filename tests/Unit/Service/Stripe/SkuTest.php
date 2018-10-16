<?php

namespace Tests\Unit\Service\Stripe;

use Carbon\Carbon;
use App\Service\Stripe\Sku;
use PHPUnit\Framework\TestCase;

class SkuTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_simple_sku()
    {
        $sku = new Sku('proj_abcd', 10000, 'dkk', 1);

        $this->assertSame([
            'product' => 'proj_abcd',
            'active' => true,
            'price' => 10000,
            'currency' => 'dkk',
            'inventory' => [
                'type' => 'finite',
                'quantity' => 1,
            ],
        ], $sku->toArray());
    }

    /**
     * @test
     */
    public function can_add_additional_data()
    {
        $sku = new Sku('proj_abcd', 10000, 'dkk', 1);
        $sku->setPrice(20000);
        $sku->setCurrency('usd');
        $sku->setStock(2);
        $sku->setActive(false);
        $sku->setImage('http://example.com/1.jpg');

        $this->assertSame([
            'product' => 'proj_abcd',
            'active' => false,
            'price' => 20000,
            'currency' => 'usd',
            'inventory' => [
                'type' => 'finite',
                'quantity' => 2,
            ],
            'image' => 'http://example.com/1.jpg',
        ], $sku->toArray());
    }

    /**
     * @test
     */
    public function can_set_auto_generated_data()
    {
        $sku = new Sku('proj_abcd', 10000, 'dkk', 1);
        $sku->setId('sku_abcd');
        $sku->setCreated($created = Carbon::now());

        $this->assertSame([
            'id' => 'sku_abcd',
            'product' => 'proj_abcd',
            'active' => true,
            'price' => 10000,
            'currency' => 'dkk',
            'inventory' => [
                'type' => 'finite',
                'quantity' => 1,
            ],
            'created' => (int) $created->format('U'),
        ], $sku->toArray());
    }
}
