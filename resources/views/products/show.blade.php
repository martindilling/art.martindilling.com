<?php
/**
 * @var \App\Service\Stripe\Product $product
 */
?>
@extends('layouts.app')

@section('body')
    <div class="h-full font-sans">
        <div class="h-full flex justify-center">
            <div class="w-full">
                <h1 class="text-2xl text-indigo-darker font-bold my-10 text-center">
                    <a href="{{ route('products.index') }}"
                       class="no-underline hover:underline text-indigo-darker">
                        {{ __('Art by ') }}Martin Dilling-Hansen
                    </a>
                </h1>

                <div class="container mx-auto flex flex-wrap justify-center">
                    <div class="w-full md:w-3/4 px-4 mb-8">
                        <div class="block relative p-1 border shadow bg-grey-lightest">
                            <img src="{{ $product->cover() }}" alt="">
                            <div class="px-3 pt-2 pb-3">
                                @if($product->isSold())
                                    <div class="float-right bg-red text-white font-bold py-2 px-4 border-b-4 border-red-dark rounded">
                                        Sold
                                    </div>
                                @else
                                    {{--<button class="float-right bg-blue hover:bg-blue-light text-white font-bold py-2 px-4 border-b-4 border-blue-dark hover:border-blue rounded">--}}
                                        {{--Buy {{ $product->formattedPrice() }}--}}
                                    {{--</button>--}}
                                    <form action="{{ route('products.buy', ['slug' => $product->slug()]) }}" method="POST" class="float-right">
                                        @csrf
                                        <script
                                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                            data-key="{{ config('services.stripe.key') }}"
                                            data-amount="{{ $product->price() }}"
                                            data-label="Buy {{ $product->formattedPrice() }}"
                                            data-name="Art by Martin Dilling-Hansen"
                                            data-description="Buying the piece: {{ $product->name() }}"
                                            data-image="{{ $product->cover() }}"
                                            data-locale="auto"
                                            data-currency="dkk"
                                            data-shipping-address="true">
                                        </script>
                                    </form>
                                @endif
                                <h2 class="text-2xl text-indigo-darker">
                                    {{ $product->name() }}
                                </h2>
                                <div class="text-sm text-grey-dark">
                                    {{ $product->caption() }}
                                </div>
                                <hr class="border-t border-grey-light">
                                <div class="text-sm text-grey-dark">
                                    {{ $product->description() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
