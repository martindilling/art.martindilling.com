<?php

namespace App;

use Illuminate\Support\Carbon;
use Stripe\SKU;
use Stripe\StripeObject;

class Product
{
    /** @var string */
    public $id;

    /** @var string */
    public $sku;

    /** @var string */
    public $name;

    /** @var string */
    public $caption;

    /** @var string */
    public $description;

    /** @var string */
    public $cover;

    /** @var array */
    public $images;

    /** @var int */
    public $stock;

    /** @var int */
    public $price;

    /** @var string */
    public $currency;

    /** @var string */
    public $url;

    /** @var StripeObject */
    public $meta;

    /** @var \Carbon\Carbon */
    public $created;

    public static function fromStripe(SKU $sku, \Stripe\Product $product) : Product
    {
        $instance = new static();
        $instance->id = $product->id;
        $instance->sku = $sku->id;
        $instance->name = $product->name;
        $instance->caption = $product->caption;
        $instance->description = $product->description;
        $instance->cover = $sku->image;
        $instance->images = $product->images;
        $instance->stock = $sku->inventory->quantity;
        $instance->price = $sku->price;
        $instance->currency = $sku->currency;
        $instance->url = $product->url;
        $instance->meta = $product->metadata;
        $instance->created = Carbon::createFromTimestamp($sku->created);

        return $instance;
    }

    public function formattedPrice() : string
    {
        $price = number_format($this->price / 100, 2, ',', '.') . ' kr.';
        $price = str_replace(',00', '', $price);

        return $price;
    }

    public function slug() : string
    {
        return str_replace('https://art.martindilling.com/p/', '', $this->url);
    }

    public function isSold() : bool
    {
        return $this->stock === 0;
    }
}


/*
    0 => SKU {#623 ▼
      +"id": "sku_DRQmRLZTXRCDbW"
      +"object": "sku"
      +"active": true
      +"attributes": []
      +"created": 1534612111
      +"currency": "dkk"
      +"image": "https://art.martindilling.com/i/holes/cover.jpg"
      +"inventory": StripeObject {#628 ▼
        +"quantity": 1
        +"type": "finite"
        +"value": null
      }
      +"livemode": false
      +"metadata": StripeObject {#633}
      +"package_dimensions": null
      +"price": 30000
      +"product": Product {#724 ▼
        +"id": "prod_DRQl5IIrQPCADK"
        +"object": "product"
        +"active": true
        +"attributes": []
        +"caption": "Painting on 10x10cm canvas"
        +"created": 1534612053
        +"deactivate_on": []
        +"description": "Abstract, beads"
        +"images": array:1 [▼
          0 => "https://art.martindilling.com/i/holes/cover.jpg"
        ]
        +"livemode": false
        +"metadata": StripeObject {#729}
        +"name": "Holes"
        +"package_dimensions": null
        +"shippable": true
        +"type": "good"
        +"updated": 1534612094
        +"url": "https://art.martindilling.com/p/holes"
      }
      +"updated": 1534612111
    }
 */
