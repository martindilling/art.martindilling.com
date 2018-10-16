<?php

namespace Tests\Unit\Service\Stripe;

use PHPUnit\Framework\TestCase;
use App\Service\Stripe\PackageDimensions;

class PackageDimensionsTest extends TestCase
{
    /**
     * @test
     */
    public function can_build_from_inches_and_ounces()
    {
        $dimensions = new PackageDimensions(
            1.1,
            2.2,
            3.3,
            4.4
        );

        $this->assertSame([
            'height' => 1.1,
            'width' => 2.2,
            'length' => 3.3,
            'weight' => 4.4,
        ], $dimensions->toArray());
    }

    /**
     * @test
     */
    public function always_round_to_two_digits()
    {
        $dimensions = new PackageDimensions(
            1.1111,
            2.2222,
            3.3333,
            4.4444
        );

        $this->assertSame([
            'height' => 1.11,
            'width' => 2.22,
            'length' => 3.33,
            'weight' => 4.44,
        ], $dimensions->toArray());
    }

    /**
     * @test
     */
    public function can_build_from_cm_and_grams()
    {
        $dimensions = PackageDimensions::fromMetrics(
            10,
            20,
            30,
            400
        );

        $this->assertSame([
            'height' => 3.94,
            'width' => 7.87,
            'length' => 11.81,
            'weight' => 14.11,
        ], $dimensions->toArray());
    }

    /**
     * @test
     */
    public function store_high_precision_so_should_be_able_to_get_back_metrics()
    {
        $dimensions = PackageDimensions::fromMetrics(
            10,
            20,
            30,
            400
        );

        $this->assertSame([
            'height' => 10.0,
            'width' => 20.0,
            'length' => 30.0,
            'weight' => 400.0,
        ], $dimensions->inMetrics());
    }
}
