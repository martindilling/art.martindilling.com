<?php
/**
 * @var \App\Product $product
 */
?>
@extends('layouts.app')

@section('body')
    <div class="h-full font-sans">
        <div class="h-full flex justify-center">
            <div class="w-full">
                <h1 class="text-2xl text-indigo-darker font-bold my-10 text-center">
                    <a href="{{ route('products.index') }}"
                       class="no-underline hover:underline cursor-default text-indigo-darker">
                        {{ __('Art by ') }}Martin Dilling-Hansen
                    </a>
                </h1>

                <div class="container mx-auto flex flex-wrap justify-center">
                    <div class="w-full md:w-3/4 px-6 py-4 mb-8 bg-red-lighter text-red-darker">
                        {!! $message !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
