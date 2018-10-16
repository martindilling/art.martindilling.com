<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Stripe\Stripe;
use Illuminate\Contracts\Support\Responsable;
use Storage;
use Stripe\SKU as StripeSKU;
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
        $stripe = new Stripe();

        $product = $stripe->product('prod_DkYocfx8WCs4Ol');
        dump($product);
        $product->setName('Holes');
        $product->sku()->setPrice(30000);
        $product = $stripe->save($product);

        dump($product);
        dump($product->toArray());
        dd('');

        $product = $stripe->product('prod_DkYocfx8WCs4Ol');
        dump($product);
        dump($product->toArray());
        dd('');

        $products = $stripe->products();
        dump($products);
        dump($products->toArray());
        dd('');

        $skus = collect(StripeSKU::all([
            'active' => true,
        ])->data);

        $stripeProducts = collect(StripeProduct::all([
            'ids' => $skus->pluck('product')->all(),
        ])->data);

        $products = $skus->map(function (StripeSKU $sku) use ($stripeProducts) {
            return Product::fromStripe(
                $sku,
                $stripeProducts->where('id', $sku->product)->first()
            );
        });

        $products = $products->sortByDesc('stock');

        return view('admin.products.index', ['products' => $products]);
    }

    /**
     * Show the application dashboard.
     *
     * @return Responsable
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Show the application dashboard.
     *
     * @param string $slug
     *
     * @return Responsable
     */
    public function store()
    {
        $images = collect();
        /** @var \Illuminate\Http\UploadedFile $image */
        foreach (request()->file('images') as $key => $image) {
            $path = Storage::putFileAs(
                'public/' . request('slug'),
                $image,
                "{$key}.{$image->getClientOriginalExtension()}",
                'public'
            );

            $images[] = asset(str_replace('public/', 'storage/', $path));
        }

        /** @var StripeProduct $product */
        $product = StripeProduct::create([
            'name' => request('name'),
            'type' => 'good',
            'caption' => request('caption'),
            'description' => request('description'),
            'images' => $images->toArray(),
            'package_dimensions' => [
                'height' => round(request('height') / 2.54, 2),
                'width' => round(request('width') / 2.54, 2),
                'length' => round(request('thickness') / 2.54, 2),
                'weight' => round(request('weight') / 28.349523125, 2),
            ],
        ]);

        /** @var StripeSKU $sku */
        $sku = StripeSKU::create([
            'product' => $product->id,
            'price' => request('price'),
            'currency' => 'dkk',
            'image' => $images->first(),
            'inventory' => [
                'type' => 'finite',
                'quantity' => 1,
            ],
        ]);
        dump($product);
        dd($sku);


        return view('admin.products.show', ['product' => Product::fromStripe($sku, $product)]);
    }
}
