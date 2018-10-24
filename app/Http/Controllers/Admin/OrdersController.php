<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CheckoutFailed;
use App\Http\Controllers\Controller;
use App\Order;
use App\Service\Stripe\Stripe;
use Illuminate\Support\Collection;
use Stripe\Charge;
use Stripe\Order as StripeOrder;
use Stripe\Customer as StripeCustomer;
use Stripe\SKU;

class OrdersController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Error\Api
     */
    public function index()
    {
        $stripeOrders = collect(StripeOrder::all([])->data);

        $orders = $stripeOrders->map(function (StripeOrder $order) {
            return Order::fromStripe($order);
        });

        $skuIds = $orders->reduce(function (Collection $collection, Order $order) {
            return $collection->merge($order->skuItems()->pluck('parent'));
        }, collect())->unique();


        $skus = collect(SKU::all([
            'ids' => $skuIds->toArray(),
        ])->data)->keyBy('id');

//        dd($skus);

        $order = ['created', 'paid', 'fulfilled', 'returned', 'canceled'];
        $orders = $orders->sort(function (Order $a, Order $b) use ($order) {
            $pos_a = array_search($a->status, $order);
            $pos_b = array_search($b->status, $order);
            return $pos_a - $pos_b;
        });
        $orders = $orders->groupBy('status');

//        dump($stripeOrders);
//        dd($orders);

        return view('admin.orders.index', ['orders' => $orders, 'skus' => $skus]);
    }

    public function markShipped(string $id)
    {
        /** @var StripeOrder $stripeOrder */
        $stripeOrder = StripeOrder::retrieve(['id' => $id, 'expand' => ['items.parent']]);
dd($stripeOrder);
//        $product = (new Stripe())->product($stripeOrder->);
        $product->sku()->incStock();
        $this->stripe->saveProduct($product);

        $stripeOrder->pay(['customer' => $stripeOrder->customer]);
//        $order = Order::fromStripe($stripeOrder);
//
//        /** @var Charge $charge */
//        $charge = Charge::create(array(
//            "amount" => $order->amount,
//            "currency" => $order->currency,
//            "customer" => $order->customer,
////            "capture" => false,
//            "shipping" => $order->shipping->jsonSerialize(),
//            "order" => $order->id,
//            "description" => 'Payment for ' . $order->skuItems()->pluck('description')->implode(', ')
//        ));
//
//        StripeOrder::update($id, ['charge' => $charge->id]);
//        StripeOrder::update($id, ['status' => 'paid']);

        return back();

        dump('markShipped');
        dump($charge);
        dd($stripeOrder);
    }

    public function markFulfilled(string $id)
    {
        /** @var StripeOrder $stripeOrder */
        StripeOrder::update($id, ['status' => 'fulfilled']);

        return back();

        dump('markFulfilled');
        dd($stripeOrder);
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

        return view('admin.products.show', ['product' => $product]);
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

        return view('admin.products.show', ['product' => $product]);
    }
}
