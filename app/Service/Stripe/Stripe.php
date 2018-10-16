<?php

namespace App\Service\Stripe;

use Carbon\Carbon;
use Stripe\Error\Api;
use Stripe\SKU as StripeSKU;
use Stripe\Product as StripeProduct;
use Illuminate\Support\Collection;

class Stripe
{
    /**
     * @return \Illuminate\Support\Collection|\App\Service\Stripe\Product[]
     * @throws \Stripe\Error\Api
     */
    public function products() : Collection
    {
        $stripeSkus = collect(StripeSKU::all([
            'active' => true,
        ])->data);

        $stripeProducts = collect(StripeProduct::all([
            'ids' => $stripeSkus->pluck('product')->all(),
        ])->data);

        $products = $stripeSkus->map(function (StripeSKU $stripeSku) use ($stripeProducts) {
            /** @var StripeProduct $stripeProduct */
            $stripeProduct = $stripeProducts->where('id', $stripeSku->product)->first();

            return $this->buildProductFrom($stripeProduct, $stripeSku);
        });

        $products = $products->sortByDesc('stock');

        return $products;
    }

    /**
     * @param string $id
     * @return \App\Service\Stripe\Product
     * @throws \Stripe\Error\Api
     */
    public function product(string $id) : Product
    {
        /** @var StripeProduct $stripeProduct */
        $stripeProduct = StripeProduct::retrieve($id);
        /** @var StripeSKU $stripeSku */
        $stripeSku = array_first(StripeSKU::all(['product' => $id])->data);

        return $this->buildProductFrom($stripeProduct, $stripeSku);
    }

    public function save(Product $product) : Product
    {
        /** @var StripeProduct $stripeProduct */
        $stripeProduct = StripeProduct::update($product->id(), $product->toArray());
        /** @var StripeSKU $stripeSku */
        $stripeSku = StripeSKU::update($product->sku()->id(), $product->sku()->toArray());

        return $this->buildProductFrom($stripeProduct, $stripeSku);
    }

    private function buildProductFrom(StripeProduct $stripeProduct, StripeSKU $stripeSku) : Product
    {
        $product = new Product($stripeProduct->name);
        $product->setId($stripeProduct->id);
        $product->setActive($stripeProduct->active);
        $product->setCaption($stripeProduct->caption);
        $product->setDescription($stripeProduct->description);

        $product->setImages(new Images($stripeProduct->images));
        $product->setPackageDimensions(
            new PackageDimensions(
                $stripeProduct->package_dimensions->height,
                $stripeProduct->package_dimensions->width,
                $stripeProduct->package_dimensions->length,
                $stripeProduct->package_dimensions->weight
            )
        );
        $product->setUrl($stripeProduct->url);
        $product->setCreated(Carbon::createFromTimestamp($stripeProduct->created));

        $sku = new Sku($stripeProduct->id, $stripeSku->price, $stripeSku->currency, $stripeSku->inventory->quantity);
        $sku->setId($stripeSku->id);
        $sku->setActive($stripeSku->active);
        $sku->setImage($stripeSku->image);
        $sku->setCreated(Carbon::createFromTimestamp($stripeSku->created));
        $product->setSku($sku);

        return $product;
    }
}
