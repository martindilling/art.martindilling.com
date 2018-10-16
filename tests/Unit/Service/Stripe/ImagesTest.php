<?php

namespace Tests\Unit\Service\Stripe;

use App\Service\Stripe\Images;
use PHPUnit\Framework\TestCase;

class ImagesTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_without_images()
    {
        $images = new Images();

        $this->assertSame([], $images->toArray());
    }

    /**
     * @test
     */
    public function can_construct_with_images()
    {
        $images = new Images([
            'http://example.com/1.jpg',
            'http://example.com/2.jpg',
        ]);

        $this->assertSame([
            'http://example.com/1.jpg',
            'http://example.com/2.jpg',
        ], $images->toArray());
    }

    /**
     * @test
     */
    public function can_add_images()
    {
        $images = new Images();
        $images->add('http://example.com/1.jpg');
        $images->add('http://example.com/2.jpg');

        $this->assertSame([
            'http://example.com/1.jpg',
            'http://example.com/2.jpg',
        ], $images->toArray());
    }

    /**
     * @test
     */
    public function can_chain_add_calls()
    {
        $images = (new Images())
            ->add('http://example.com/1.jpg')
            ->add('http://example.com/2.jpg');

        $this->assertSame([
            'http://example.com/1.jpg',
            'http://example.com/2.jpg',
        ], $images->toArray());
    }

    /**
     * @test
     */
    public function can_only_add_8_images()
    {
        $images = new Images();
        $images->add('http://example.com/1.jpg');
        $images->add('http://example.com/2.jpg');
        $images->add('http://example.com/3.jpg');
        $images->add('http://example.com/4.jpg');
        $images->add('http://example.com/5.jpg');
        $images->add('http://example.com/6.jpg');
        $images->add('http://example.com/7.jpg');
        $images->add('http://example.com/8.jpg');
        $images->add('http://example.com/9.jpg');

        $this->assertSame([
            'http://example.com/1.jpg',
            'http://example.com/2.jpg',
            'http://example.com/3.jpg',
            'http://example.com/4.jpg',
            'http://example.com/5.jpg',
            'http://example.com/6.jpg',
            'http://example.com/7.jpg',
            'http://example.com/8.jpg',
        ], $images->toArray());
    }

    /**
     * @test
     */
    public function can_only_add_8_images_from_constructor()
    {
        $images = new Images([
            'http://example.com/1.jpg',
            'http://example.com/2.jpg',
            'http://example.com/3.jpg',
            'http://example.com/4.jpg',
            'http://example.com/5.jpg',
            'http://example.com/6.jpg',
            'http://example.com/7.jpg',
            'http://example.com/8.jpg',
            'http://example.com/9.jpg',
        ]);

        $this->assertSame([
            'http://example.com/1.jpg',
            'http://example.com/2.jpg',
            'http://example.com/3.jpg',
            'http://example.com/4.jpg',
            'http://example.com/5.jpg',
            'http://example.com/6.jpg',
            'http://example.com/7.jpg',
            'http://example.com/8.jpg',
        ], $images->toArray());
    }
}
