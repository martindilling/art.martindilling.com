<?php

namespace App\Http\Controllers;

use App\Service\StripeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stripe = new StripeService();

//        $product = $stripe->findProduct('prod_DqD0KrQzj6D3zM');
//
//        dd($product);

//        $products = $stripe->allProducts();
//
//        dd($products);

        $order = $stripe->findOrder('or_1DOZfqLO5PuON07OsLp7n2Ub');

        return view('products.confirmation', ['order' => $order]);
        dd($order);


        return view('home');
    }
}
