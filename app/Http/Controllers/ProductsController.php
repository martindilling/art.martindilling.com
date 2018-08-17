<?php

namespace App\Http\Controllers;

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
        $result = \Stripe\Product::all([
            'active' => true,
            'shippable' => true,
        ]);

        dd($result);

        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        return view('home');
    }
}
