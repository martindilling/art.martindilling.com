<?php

namespace App\Service\Stripe;

use Illuminate\Contracts\Support\Arrayable;

class PackageDimensions implements Arrayable
{
    /** @var float */
    private $heightInInches;

    /** @var float */
    private $widthInInches;

    /** @var float */
    private $lengthInInches;

    /** @var float */
    private $weightInOunces;

    /**
     * @param float $heightInInches
     * @param float $widthInInches
     * @param float $lengthInInches
     * @param float $weightInOunces
     */
    public function __construct(
        float $heightInInches,
        float $widthInInches,
        float $lengthInInches,
        float $weightInOunces
    ) {
        $this->heightInInches = $heightInInches;
        $this->widthInInches = $widthInInches;
        $this->lengthInInches = $lengthInInches;
        $this->weightInOunces = $weightInOunces;
    }

    public static function fromMetrics(
        float $heightInCm,
        float $widthInCm,
        float $lengthInCm,
        float $weightInGrams
    ) : PackageDimensions {
        return new static(
            $heightInCm / 2.54,
            $widthInCm / 2.54,
            $lengthInCm / 2.54,
            $weightInGrams / 28.349523125
        );
    }

    public function heightInInches() : float
    {
        return round($this->heightInInches, 2);
    }

    public function widthInInches() : float
    {
        return round($this->widthInInches, 2);
    }

    public function lengthInInches() : float
    {
        return round($this->lengthInInches, 2);
    }

    public function weightInOunces() : float
    {
        return round($this->weightInOunces, 2);
    }

    public function heightInCm() : float
    {
        return round($this->heightInInches * 2.54, 2);
    }

    public function widthInCm() : float
    {
        return round($this->widthInInches * 2.54, 2);
    }

    public function lengthInCm() : float
    {
        return round($this->lengthInInches * 2.54, 2);
    }

    public function weightInGrams() : float
    {
        return round($this->weightInOunces * 28.349523125, 2);
    }

    public function isEmpty() : bool
    {
        return !$this->heightInInches
            || !$this->widthInInches
            || !$this->lengthInInches
            || !$this->weightInOunces;
    }

    /**
     * Get the instance as an array.
     *
     * @return array|null
     */
    public function toImperialArray() : ?array
    {
        if ($this->isEmpty()) {
            return null;
        }

        return [
            'height' => round($this->heightInInches, 2),
            'width' => round($this->widthInInches, 2),
            'length' => round($this->lengthInInches, 2),
            'weight' => round($this->weightInOunces, 2),
        ];
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return [
            'height' => round($this->heightInInches, 2),
            'width' => round($this->widthInInches, 2),
            'length' => round($this->lengthInInches, 2),
            'weight' => round($this->weightInOunces, 2),
        ];
    }
}
