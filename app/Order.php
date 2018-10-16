<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Stripe\Order as StripeOrder;
use Stripe\StripeObject;

class Order
{
    /** @var string */
    public $id;

    /** @var string */
    public $charge_id;

    /** @var string */
    public $amount;

    /** @var string */
    public $currency;

    /** @var string */
    public $status;

    /** @var StripeObject */
    public $status_transitions;

    /** @var string */
    public $email;

    /** @var string */
    public $customer;

    /** @var \Stripe\OrderItem[]|\Illuminate\Support\Collection */
    public $items;

    /** @var StripeObject */
    public $shipping;

    /** @var StripeObject */
    public $meta;

    /** @var Carbon */
    public $created;


    public static function fromStripe(StripeOrder $order) : Order
    {
        $instance = new static();
        $instance->id = $order->id;
        $instance->charge_id = $order->charge;
        $instance->amount = $order->amount;
        $instance->currency = $order->currency;
        $instance->status = $order->status;
        $instance->status_transitions = $order->status_transitions;
        $instance->email = $order->email;
        $instance->customer = $order->customer;
        $instance->items = collect($order->items);
        $instance->shipping = $order->shipping;
        $instance->meta = $order->metadata;
        $instance->created = Carbon::createFromTimestamp($order->created);

        return $instance;
    }

    public function formattedAmount() : string
    {
        $amount = number_format($this->amount / 100, 2, ',', '.') . ' kr.';
        $amount = str_replace(',00', '', $amount);

        return $amount;
    }

    public function skuItems() : Collection
    {
        return $this->items->where('type', 'sku');
    }

    public function isSold() : bool
    {
        return $this->stock === 0;
    }
}


/*
    [0] => Stripe\Order JSON: {
      "id": "or_1D0fUbLO5PuON07OxU9UXtka",
      "object": "order",
      "amount": 60000,
      "amount_returned": null,
      "application": null,
      "application_fee": null,
      "charge": "ch_1D0fUdLO5PuON07OFVCk2Xpo",
      "created": 1534641249,
      "currency": "dkk",
      "customer": null,
      "email": "martindilling@gmail.com",
      "items": [
        {
          "object": "order_item",
          "amount": 60000,
          "currency": "dkk",
          "description": "Simple Symmetry",
          "parent": "sku_DRPtjb5QTWQ54I",
          "quantity": 1,
          "type": "sku"
        },
        {
          "object": "order_item",
          "amount": 0,
          "currency": "dkk",
          "description": "Taxes (included)",
          "parent": null,
          "quantity": null,
          "type": "tax"
        },
        {
          "object": "order_item",
          "amount": 0,
          "currency": "dkk",
          "description": "Free shipping",
          "parent": "ship_free-shipping",
          "quantity": null,
          "type": "shipping"
        }
      ],
      "livemode": false,
      "metadata": {
      },
      "returns": {
        "object": "list",
        "data": [

        ],
        "has_more": false,
        "total_count": 0,
        "url": "/v1/order_returns?order=or_1D0fUbLO5PuON07OxU9UXtka"
      },
      "selected_shipping_method": "ship_free-shipping",
      "shipping": {
        "address": {
          "city": "Hedehusene",
          "country": "DK",
          "line1": "Liselundager 8D",
          "line2": null,
          "postal_code": "2640",
          "state": "85"
        },
        "carrier": null,
        "name": "Martin Dilling-Hansen",
        "phone": null,
        "tracking_number": null
      },
      "shipping_methods": [
        {
          "id": "ship_free-shipping",
          "amount": 0,
          "currency": "dkk",
          "delivery_estimate": null,
          "description": "Free shipping"
        }
      ],
      "status": "fulfilled",
      "status_transitions": {
        "canceled": null,
        "fulfiled": 1534642364,
        "paid": 1534641252,
        "returned": null
      },
      "updated": 1534642364
    }
 */
