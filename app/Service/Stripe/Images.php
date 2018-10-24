<?php

namespace App\Service\Stripe;

use Illuminate\Contracts\Support\Arrayable;

class Images implements Arrayable
{
    /** @var string[] */
    private $images = [];

    /**
     * Images constructor.
     * @param string[] $images
     */
    public function __construct(array $images = [])
    {
        foreach ($images as $image) {
            $this->add($image);
        }
    }

    public function first() : ?string
    {
        return array_first($this->images);
    }

    public function add(string $image) : Images
    {
        if (count($this->images) < 8) {
            $this->images[] = $image;
        }

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return $this->images;
    }
}
