<?php
/**
 * @var \App\Service\Order $order
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
                            <div>
                                Thank you for ordering {{ $order->orderedProduct()->name }}.
                                <br>
                                I will wrap it up and send it to the address you provided:
                                <br>
                                {!! $order->address() !!}
                            </div>
                            <img src="{{ $order->orderedProduct()->cover() }}" alt="">
                            <div class="px-3 pt-2 pb-3">
                                <h2 class="text-2xl text-indigo-darker">
                                    {{ $order->orderedProduct()->name }}
                                </h2>
                                <div class="text-sm text-grey-dark">
                                    {{ $order->orderedProduct()->caption }}
                                </div>
                                <hr class="border-t border-grey-light">
                                <div class="text-sm text-grey-dark">
                                    {{ $order->orderedProduct()->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
