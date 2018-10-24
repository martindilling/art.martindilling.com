<?php

namespace App\Service;

use Stripe\SKU as StripeSku;
use Stripe\Product as StripeProduct;
use Stripe\Customer as StripeCustomer;
use Stripe\Order as StripeOrder;
use Stripe\OrderItem as StripeOrderItem;

/**
 * @mixin StripeOrder
 */
class Order
{
    /** @var StripeOrder */
    private $order;

    /**
     * @param StripeOrder $order
     */
    public function __construct(StripeOrder $order)
    {
        $this->order = $order;
    }

    public function order() : StripeOrder
    {
        return $this->order;
    }

    public function orderedProduct() : ?Product
    {
        /** @var StripeOrderItem $stripeOrderItem */
        $stripeOrderItem = collect($this->order->items)->where('type', 'sku')->first();

        return new Product($stripeOrderItem->parent);
    }

    public function address() : string
    {
        $address = $this->order->shipping->address;
        $line2 = $address->line2 ? "{$address->line2}<br>" : '';

        return "{$address->line1}<br>$line2 {$address->country}-{$address->postal_code} {$address->city}";
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if (isset($this->order->{$name})) {
            return $this->order->{$name};
        }

        throw new \Exception("Unknown property {$name}");
    }


}
