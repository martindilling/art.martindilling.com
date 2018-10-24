<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutFailed;
use App\Service\Stripe\Stripe;
use App\Service\StripeService;
use Stripe\Order;
use Stripe\SKU;
use App\Product;
use Stripe\Product as StripeProduct;

class ProductsController extends Controller
{
    /**
     * @var \App\Service\Stripe\Stripe
     */
    private $s;

    /**
     * @var \App\Service\StripeService
     */
    private $stripe;

    /**
     * @param Stripe $s
     * @param \App\Service\StripeService $stripe
     */
    public function __construct(Stripe $s, StripeService $stripe)
    {
        $this->s = $s;
        $this->stripe = $stripe;
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     * @throws \Stripe\Error\Base
     */
    public function index()
    {
        $products = $this->stripe->allProducts();

        return view('products.index', ['products' => $products]);
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\Contracts\Support\Renderable
     * @throws \Stripe\Error\Base
     */
    public function show(string $slug)
    {
        $product = $this->stripe->findProductBySlug($slug);

        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the application dashboard.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Error\Base
     */
    public function buy(string $slug)
    {
        $product = $this->stripe->findProductBySlug($slug);

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

        $customer = $this->stripe->findCustomerByEmail($customerData['email']);
        $customer = $this->stripe->saveCustomer($customer, $customerData);
        $orderId = $this->stripe->createOrder($product, $customer);
        $order = $this->stripe->findOrder($orderId);

        // TODO: Send confirmation email

        return view('products.confirmation', ['order' => $order]);
    }


}
