<?php

namespace App\Service;

use Stripe\SKU as StripeSku;
use Stripe\Product as StripeProduct;

/**
 * @mixin StripeProduct
 * @mixin StripeSku
 */
class Product
{
    /** @var StripeProduct */
    private $product;

    /** @var StripeSku */
    private $sku;

    /**
     * @param StripeSku $sku
     */
    public function __construct(StripeSku $sku)
    {
        $this->sku = $sku;
        $this->product = $sku->product;
    }

    public function product() : StripeProduct
    {
        return $this->product;
    }

    public function sku() : StripeSku
    {
        return $this->sku;
    }

    public function cover() : string
    {
        return $this->sku->image;
    }

    public function slug() : string
    {
        return $this->product->metadata['slug'] ?? 'unknown';
    }

    public function reserved() : int
    {
        return $this->product->metadata['reserved'] ?? 0;
    }

    public function stock() : int
    {
        return $this->sku->inventory['quantity'] ?? 0;
    }

    public function isSold() : bool
    {
        return ($this->stock() - $this->reserved()) <= 0;
    }

    public function formattedPrice() : string
    {
        $price = number_format($this->sku->price / 100, 2, ',', '.') . ' kr.';
        $price = str_replace(',00', '', $price);

        return $price;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if (isset($this->product->{$name})) {
            return $this->product->{$name};
        }

        if (isset($this->sku->{$name})) {
            return $this->sku->{$name};
        }

        throw new \Exception("Unknown property {$name}");
    }


}
