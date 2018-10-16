<?php

namespace App\Service\Stripe;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Sku implements Arrayable
{
    /** @var string|null */
    private $id = null;

    /** @var string */
    private $productId;

    /** @var bool */
    private $active = true;

    /** @var integer */
    private $price;

    /** @var string */
    private $currency;

    /** @var integer */
    private $stock;

    /** @var string|null */
    private $image = null;

    /** @var Carbon|null */
    private $created = null;

    /**
     * @param string $productId
     * @param int $price
     * @param string $currency
     * @param int $stock
     */
    public function __construct(string $productId, int $price, string $currency, int $stock)
    {
        $this->productId = $productId;
        $this->price = $price;
        $this->currency = $currency;
        $this->stock = $stock;
    }

    public function id() : string
    {
        return $this->id;
    }

    public function setId(?string $id) : void
    {
        $this->id = $id;
    }

    public function setPrice(int $price) : void
    {
        $this->price = $price;
    }

    public function setCurrency(string $currency) : void
    {
        $this->currency = $currency;
    }

    public function setStock(int $stock) : void
    {
        $this->stock = $stock;
    }

    public function setActive(?bool $active) : void
    {
        $this->active = $active;
    }

    public function setImage(?string $image) : void
    {
        $this->image = $image;
    }

    public function setCreated(?Carbon $created) : void
    {
        $this->created = $created;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return array_filter([
//            'id' => $this->id,
            'product' => $this->productId,
            'active' => $this->active,
            'price' => $this->price,
            'currency' => $this->currency,
            'inventory' => [
                'type' => 'finite',
                'quantity' => $this->stock,
            ],
            'image' => $this->image,
//            'created' => $this->created ? (int) $this->created->format('U') : null,
        ], function ($value) { return $value !== null; });
    }
}
