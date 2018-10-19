<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutFailed;
use App\Service\Stripe\Stripe;
use Stripe\Order;
use Stripe\SKU;
use App\Product;
use Stripe\Product as StripeProduct;

class ProductsController extends Controller
{
    /**
     * @var \App\Service\Stripe\Stripe
     */
    private $stripe;

    /**
     * @param Stripe $stripe
     */
    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Error\Api
     */
    public function index()
    {
        $products = $this->stripe->products();

        return view('products.index', ['products' => $products]);
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Error\Api
     */
    public function show(string $slug)
    {
        $product = $this->stripe->productFromSlug($slug);

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
        $product = $this->stripe->productFromSlug($slug);

        $customerData = [
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
        ];

        /** @var \Stripe\Customer $customer */
        $customer = $this->stripe->customerFromEmail($customerData['email']);
        $customer = $this->stripe->saveCustomer($customer, $customerData);

        /** @var Order $order */
        $order = Order::create([
            'currency' => $product->sku()->currency(),
            'customer' => $customer->id,
            'items' => [
                [
                    'type' => 'sku',
                    'parent' => $product->sku()->id(),
                    'description' => $product->name(),
                    'quantity' => 1,
                ],
            ],
        ]);

        if ($order->amount !== $product->sku()->price()) {
            throw CheckoutFailed::priceMismatchOnCharge();
        }

//        $order->pay(['customer' => $customer->id]);

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
