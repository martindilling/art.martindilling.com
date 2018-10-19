<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Stripe\Images;
use App\Service\Stripe\PackageDimensions;
use App\Service\Stripe\Product;
use App\Service\Stripe\Sku;
use App\Service\Stripe\Stripe;
use Illuminate\Contracts\Support\Responsable;
use Storage;
use Stripe\SKU as StripeSKU;
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

        return view('admin.products.index', ['products' => $products]);
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Error\Api
     */
    public function show(string $id)
    {
        $product = $this->stripe->product($id);

        return view('admin.products.show', ['product' => $product]);
    }

    /**
     * @return Responsable
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * @return Responsable
     */
    public function store()
    {
        $images = new Images();
        /** @var \Illuminate\Http\UploadedFile $image */
        foreach (request()->file('images', []) as $key => $image) {
            $path = Storage::putFileAs(
                'public/' . request('slug'),
                $image,
                "{$key}.{$image->getClientOriginalExtension()}",
                'public'
            );

            $images->add(asset(str_replace('public/', 'storage/', $path)));
        }

        $product = new Product(request('name'));
        $product->setCaption(request('caption'));
        $product->setDescription(request('description'));
        $product->setImages($images);
        $product->setPackageDimensions(
            PackageDimensions::fromMetrics(
                request('height'),
                request('width'),
                request('thickness'),
                request('weight')
            )
        );
        $product->setUrl(route('products.show', ['slug' => request('slug')]));

        $sku = new Sku(request('price'), 'dkk', 1);
        $sku->setImage($images->first());
        $product->setSku($sku);

        $product = $this->stripe->saveProduct($product);

        return view('admin.products.show', ['product' => $product]);
    }
}
