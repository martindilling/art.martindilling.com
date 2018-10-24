<?php

namespace App\Service;

use Stripe\SKU as StripeSku;
use Stripe\Product as StripeProduct;
use Stripe\Customer as StripeCustomer;

/**
 * @mixin StripeCustomer
 */
class Customer
{
    /** @var StripeCustomer */
    private $customer;

    /**
     * @param StripeCustomer $customer
     */
    public function __construct(StripeCustomer $customer)
    {
        $this->customer = $customer;
    }

    public function customer() : StripeCustomer
    {
        return $this->customer;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if (isset($this->customer->{$name})) {
            return $this->customer->{$name};
        }

        throw new \Exception("Unknown property {$name}");
    }


}
