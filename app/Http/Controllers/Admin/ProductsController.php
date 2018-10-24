<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Stripe\Images;
use App\Service\Stripe\PackageDimensions;
use App\Service\Stripe\Product;
use App\Service\Stripe\Sku;
use App\Service\Stripe\Stripe;
use App\Service\StripeService;
use Illuminate\Contracts\Support\Responsable;
use Storage;
use Stripe\SKU as StripeSKU;
use Stripe\Product as StripeProduct;

class ProductsController extends Controller
{

    /**
     * @var \App\Service\StripeService
     */
    private $stripe;

    /**
     * @param \App\Service\StripeService $stripe
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     * @throws \Stripe\Error\Base
     */
    public function index()
    {
        $products = $this->stripe->allProducts();

        return view('admin.products.index', ['products' => $products]);
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     * @throws \Stripe\Error\Base
     */
    public function show(string $id)
    {
        $product = $this->stripe->findProduct($id);

        return view('admin.products.show', ['product' => $product]);
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Stripe\Error\Base
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

        $stripe = new StripeService();

        $productId = $stripe->createProduct(
            request('slug'),
            request('name'),
            request('caption'),
            request('description'),
            request('price'),
            $images,
            PackageDimensions::fromMetrics(
                request('height'),
                request('width'),
                request('thickness'),
                request('weight')
            )
        );

        return redirect()->route('admin.products.show', ['product' => $productId]);
    }
}
