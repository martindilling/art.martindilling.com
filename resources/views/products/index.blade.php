<?php
/**
 * @var \App\Service\Product[]|Illuminate\Support\Collection $products
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

                <div class="container mx-auto flex flex-wrap">
                    @forelse($products as $product)
                        <div class="w-1/2 px-4 mb-8">
                            <a
                                href="{{ route('products.show', ['slug' => $product->slug()]) }}"
                                class="block relative p-1 border shadow hover:shadow-md hover:border-indigo-dark no-underline bg-grey-lightest"
                            >
                                <div class="absolute pin-t pin-r mt-3 -mr-1 px-3 py-2 @if($product->isSold()) bg-red text-red-lightest @else bg-indigo text-indigo-lightest @endif shadow">
                                    @if($product->isSold())
                                        Sold
                                    @else
                                        {{ $product->formattedPrice() }}
                                    @endif
                                </div>
                                <img src="{{ $product->cover() }}" alt="">
                                <div class="px-3 pt-2 pb-3">
                                    <h2 class="text-2xl text-indigo-darker">
                                        {{ $product->name }}
                                    </h2>
                                    <div class="text-sm text-grey-dark">
                                        {{ $product->caption }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="w-full py-32 cursor-default text-3xl text-center text-grey">
                            No products found
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
@endsection
