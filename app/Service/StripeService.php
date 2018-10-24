<?php

namespace App\Service;

use App\Service\Stripe\Images;
use App\Service\Stripe\PackageDimensions;
use Illuminate\Support\Collection;
use Stripe\Error\InvalidRequest;
use Stripe\SKU as StripeSku;
use Stripe\Order as StripeOrder;
use Stripe\Product as StripeProduct;
use Stripe\Customer as StripeCustomer;

class StripeService
{
    /**
     * @param string $slug
     * @param string $name
     * @param string $caption
     * @param string $description
     * @param int $price
     * @param \App\Service\Stripe\Images $images
     * @param \App\Service\Stripe\PackageDimensions $packageDimensions
     * @return string
     * @throws \Stripe\Error\Base
     */
    public function createProduct(
        string $slug,
        string $name,
        string $caption,
        string $description,
        int $price,
        Images $images,
        PackageDimensions $packageDimensions
    ) : string {
        $metadata = [
            'slug' => $slug,
            'reserved' => 0,
        ];

        /** @var StripeProduct $stripeProduct */
        $stripeProduct = StripeProduct::create(array_filter_nulls([
            'active' => true,
            'name' => $name,
            'type' => 'good',
            'caption' => $caption,
            'description' => $description,
            'images' => $images->toArray(),
            'package_dimensions' => $packageDimensions->toImperialArray(),
            'url' => $this->urlFromSlug($slug),
            'metadata' => $metadata,
        ]));

        /** @var StripeSku $stripeSku */
        $stripeSku = StripeSku::create(array_filter_nulls([
            'product' => $stripeProduct->id,
            'active' => true,
            'price' => $price,
            'currency' => 'dkk',
            'inventory' => [
                'type' => 'finite',
                'quantity' => 1,
            ],
            'image' => $images->first(),
            'package_dimensions' => $packageDimensions->toImperialArray(),
            'metadata' => $metadata,
        ]));

        return $stripeProduct->id;
    }

    /**
     * @param \App\Service\Product $product
     * @return int
     * @throws \Stripe\Error\Base
     */
    public function reserveProduct(Product $product) : int
    {
        $stripeProduct = $product->product();
        $stripeSku = $product->sku();
        $reservedCount = $stripeProduct->metadata['reserved'];

        $stripeProduct->metadata['reserved'] = $reservedCount + 1;
        $stripeProduct->save();
        $stripeSku->metadata['reserved'] = $reservedCount + 1;
        $stripeSku->save();

        return $reservedCount + 1;
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \Stripe\Error\Base
     */
    public function allProducts() : Collection
    {
        $stripeSkus = collect(StripeSKU::all([
            'limit' => 100,
            'active' => true,
            'expand' => ['data.product'],
        ])->data);

        return $stripeSkus->mapInto(Product::class);
    }

    /**
     * @param string $productId
     * @return \App\Service\Product|null
     * @throws \Stripe\Error\Base
     */
    public function findProduct(string $productId) : ?Product
    {
        try {
            $stripeSku = array_first(StripeSKU::all([
                'limit' => 1,
                'active' => true,
                'product' => $productId,
                'expand' => ['data.product'],
            ])->data);
        } catch (InvalidRequest $e) {
            if ($e->getStripeCode() === 'resource_missing') {
                return null;
            }

            throw $e;
        }

        return $stripeSku ? new Product($stripeSku) : null;
    }

    /**
     * @param string $slug
     * @return \App\Service\Product|null
     * @throws \Stripe\Error\Base
     */
    public function findProductBySlug(string $slug) : ?Product
    {
        try {
            $stripeProduct = array_first(StripeProduct::all([
                'limit' => 1,
                'active' => true,
                'url' => $this->urlFromSlug($slug),
            ])->data);
        } catch (InvalidRequest $e) {
            if ($e->getStripeCode() === 'resource_missing') {
                return null;
            }

            throw $e;
        }

        return $this->findProduct($stripeProduct->id);
    }

    /**
     * @param string $email
     * @return \App\Service\Customer|null
     * @throws \Stripe\Error\Base
     */
    public function findCustomerByEmail(string $email) : ?Customer
    {
        try {
            $stripeCustomer = array_first(StripeCustomer::all([
                'limit' => 1,
                'email' => $email,
            ])->data);
        } catch (InvalidRequest $e) {
            if ($e->getStripeCode() === 'resource_missing') {
                return null;
            }

            throw $e;
        }

        return $stripeCustomer ? new Customer($stripeCustomer) : null;
    }

    /**
     * @param \App\Service\Customer|null $customer
     * @param array $attributes
     * @return \App\Service\Customer
     * @throws \Stripe\Error\Base
     */
    public function saveCustomer(?Customer $customer, array $attributes) : Customer
    {
        try {
            /** @var StripeCustomer $customer */
            if ($customer) {
                $customer = StripeCustomer::update($customer->id, $attributes);
            } else {
                $customer = StripeCustomer::create($attributes);
            }
        } catch (InvalidRequest $e) {
            if ($e->getStripeCode() === 'resource_missing') {
                return null;
            }

            throw $e;
        }

        return new Customer($customer);
    }

    /**
     * @param \App\Service\Product $product
     * @param \App\Service\Customer $customer
     * @return string
     * @throws \Stripe\Error\Base
     */
    public function createOrder(Product $product, Customer $customer) : string {
        /** @var StripeOrder $stripeOrder */
        $stripeOrder = StripeOrder::create([
            'currency' => $product->currency,
            'customer' => $customer->id,
            'items' => [
                [
                    'type' => 'sku',
                    'parent' => $product->sku()->id,
                    'description' => $product->name,
                    'quantity' => 1,
                ],
            ],
        ]);

        $this->reserveProduct($product);

        return $stripeOrder->id;
    }

    /**
     * @param string $orderId
     * @return Order|null
     * @throws \Stripe\Error\Base
     */
    public function findOrder(string $orderId) : ?Order {
        try {
            $stripeOrder = array_first(StripeOrder::all([
                'limit' => 1,
                'ids' => [$orderId],
                'expand' => ['data.customer', 'data.items.parent', 'data.items.parent.product'],
            ])->data);
        } catch (InvalidRequest $e) {
            if ($e->getStripeCode() === 'resource_missing') {
                return null;
            }

            throw $e;
        }

        return $stripeOrder ? new Order($stripeOrder) : null;
    }

    /**
     * @param string $slug
     * @return string
     */
    private function urlFromSlug(string $slug) : string
    {
        return route('products.show', ['slug' => $slug]);
    }
}
