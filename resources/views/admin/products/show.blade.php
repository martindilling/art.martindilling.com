<?php
/**
 * @var \App\Service\Stripe\Product $product
 */
?>
@extends('layouts.admin')

@section('body')
    <div class="h-full font-sans">
        <div class="h-full flex justify-center">
            <div class="w-full">
                <div class="container mx-auto flex flex-wrap justify-center">
                    <div class="w-full md:w-3/4 px-4 mb-8">
                        <div class="block relative p-1 border shadow bg-grey-lightest">
                            <img src="{{ $product->cover() }}" alt="">
                            <div class="px-3 pt-2 pb-3">
                                <div class="float-right @if($product->isSold()) bg-red text-red-lightest border-red-dark @else bg-indigo text-indigo-lightest border-indigo-dark @endif font-bold py-2 px-4 border-b-4  rounded">
                                    @if($product->isSold())
                                        <del>{{ $product->formattedPrice() }}</del>
                                    @else
                                        {{ $product->formattedPrice() }}
                                    @endif
                                </div>
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
