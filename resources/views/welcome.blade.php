@extends('layouts.app')

@section('body')
    <div class="h-full font-sans">
        <div class="h-full flex justify-center">
            <div class="w-full">
                <h1 class="font-thin text-grey-dark mb-8 mt-6 text-center">
                    {{ __('Art by ') }}
                    <a href="http://martindilling.com" class="no-underline hover:underline cursor-default font-thin text-grey-dark">
                        Martin Dilling-Hansen
                    </a>
                </h1>

                <div class="bg-grey-light px-4 py-12">
                    <div class="container mx-auto flex flex-wrap">
                        <div class="w-1/2 pr-5">
                            <div class="gumroad-product-embed flex justify-center items-center"
                                 data-gumroad-product-id="mdh_simple-symmetry">
                                <a href="https://gumroad.com/l/mdh_simple-symmetry">
                                    <img src="{{ asset('images/simple-symmetry_01.jpg') }}" alt="Simple Symmetry">
                                </a>
                            </div>
                        </div>
                        <div class="w-1/2 pl-5">
                            <div class="gumroad-product-embed flex justify-center items-center"
                                 data-gumroad-product-id="mdh_invasion">
                                <a href="https://gumroad.com/l/mdh_invasion">
                                    <img src="{{ asset('images/invasion_01.jpg') }}" alt="Invasion">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-grey-lighter px-4 py-12">
                    <div class="container mx-auto flex flex-wrap">
                        <div class="w-1/2 pr-5">
                            <div class="gumroad-product-embed flex justify-center items-center"
                                 data-gumroad-product-id="mdh_crossover">
                                <a href="https://gumroad.com/l/mdh_crossover">
                                    <img src="{{ asset('images/crossover_01.jpg') }}" alt="Crossover">
                                </a>
                            </div>
                        </div>
                        <div class="w-1/2 pl-5">
                            <div class="gumroad-product-embed flex justify-center items-center"
                                 data-gumroad-product-id="mdh_holes">
                                <a href="https://gumroad.com/l/mdh_holes">
                                    <img src="{{ asset('images/holes_01.jpg') }}" alt="Holes">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
