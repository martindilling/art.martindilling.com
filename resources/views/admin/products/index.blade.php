<?php
/**
 * @var \App\Service\Stripe\Product[]|Illuminate\Support\Collection $products
 */
?>
@extends('layouts.admin')

@section('body')
    <div class="h-full font-sans">
        <div class="h-full flex justify-center">
            <div class="w-full">
                <div class="container mx-auto flex flex-wrap">
                    @forelse($products as $product)
                        <div class="flex relative w-full p-4 mb-8 border shadow bg-grey-lightest">
                            <img src="{{ $product->cover() }}" class="h-48">
                            <div class="flex-1">
                                <div class="px-3 pt-2 pb-3">
                                    <a
                                        href="{{ route('admin.products.show', ['id' => $product->id()]) }}"
                                        class="block no-underline text-indigo-darker hover:text-teal-dark"
                                    >
                                        <h2 class="text-2xl">
                                            {{ $product->name() }}
                                        </h2>
                                    </a>
                                    <div class="text-base text-grey">
                                        {{ $product->slug() }}
                                        <br>
                                        {{ $product->id() }}
                                    </div>
                                    <div class="text-base text-grey">
                                        {{ $product->caption() }}
                                    </div>
                                    <div class="text-sm text-grey-dark mt-2">
                                        {{ $product->description() }}
                                    </div>
                                    <div class="absolute pin-t pin-r mt-3 -mr-1 px-3 py-2 @if($product->isSold()) bg-red text-red-lightest @else bg-indigo text-indigo-lightest @endif shadow">
                                        @if($product->isSold())
                                            <del>{{ $product->formattedPrice() }}</del>
                                        @else
                                            {{ $product->formattedPrice() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
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
