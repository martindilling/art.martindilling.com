<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutFailed;
use Stripe\Order;
use Stripe\SKU;
use App\Product;
use Stripe\Product as StripeProduct;

class ProductsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Error\Api
     */
    public function index()
    {
        $skus = collect(SKU::all([
            'active' => true,
        ])->data);

        $stripeProducts = collect(StripeProduct::all([
            'ids' => $skus->pluck('product')->all(),
        ])->data);

        $products = $skus->map(function (SKU $sku) use ($stripeProducts) {
            return Product::fromStripe(
                $sku,
                $stripeProducts->where('id', $sku->product)->first()
            );
        });

        $products = $products->sortByDesc('stock');

//        dump($skus, $stripeProducts);
//        dd($products);

        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the application dashboard.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Error\Api
     */
    public function show(string $slug)
    {
        $stripeProduct = collect(StripeProduct::all([
            'url' => 'https://art.martindilling.com/p/' . $slug,
        ])->data)->first();

        $sku = collect(SKU::all([
            'active' => true,
            'product' => $stripeProduct->id,
        ])->data)->first();

        $product = Product::fromStripe($sku, $stripeProduct);

//        dump($sku, $stripeProduct);
//        dd($product);

        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the application dashboard.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Error\Api
     */
    public function buy(string $slug)
    {
        /** @var StripeProduct $stripeProduct */
        $stripeProduct = collect(StripeProduct::all([
            'url' => 'https://art.martindilling.com/p/' . $slug,
        ])->data)->first();

        /** @var SKU $sku */
        $sku = collect(SKU::all([
            'active' => true,
            'product' => $stripeProduct->id,
        ])->data)->first();

        /** @var \Stripe\Customer $customer */
        $customer = \Stripe\Customer::create([
            'email' => request('stripeEmail'),
            'source' => request('stripeToken'),
            'shipping' =>  [
                'name' => request('stripeShippingName'),
                'address' => [
                    'line1' => request('stripeShippingAddressLine1'),
                    'city' => request('stripeShippingAddressCity'),
                    'state' => request('stripeShippingAddressState'),
                    'postal_code' => request('stripeShippingAddressZip'),
                    'country' => request('stripeShippingAddressCountryCode'),
                ],
            ],
        ]);

        /** @var Order $order */
        $order = Order::create([
            'currency' => $sku->currency,
            'customer' => $customer->id,
            'items' => [
                [
                    'type' => 'sku',
                    'parent' => $sku->id,
                    'description' => $stripeProduct->name,
                    'quantity' => 1,
                ],
            ],
        ]);

        if ($order->amount !== $sku->price) {
            throw CheckoutFailed::priceMismatchOnCharge();
        }

        $order->pay(['customer' => $customer->id]);

//        "stripeShippingName" => "Martin Dilling-Hansen"
//      "stripeShippingAddressCountry" => "Denmark"
//      "stripeShippingAddressCountryCode" => "DK"
//      "stripeShippingAddressZip" => "2640"
//      "stripeShippingAddressLine1" => "Liselundager 8D"
//      "stripeShippingAddressCity" => "Hedehusene"
//      "stripeShippingAddressState" => "85"

        dd($order);
//        dd(request());



        $product = Product::fromStripe($sku, $stripeProduct);

//        dump($sku, $stripeProduct);
//        dd($product);

        return view('products.show', ['product' => $product]);
    }
}
