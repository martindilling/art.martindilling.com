@extends('layouts.app')

@section('body')
    <div class="h-full font-sans">
        <div class="h-full flex justify-center">
            <div class="w-full">
                <h1 class="text-2xl text-indigo-darker font-bold my-10 text-center">
                    {{ __('Art by ') }}
                    <a href="http://martindilling.com"
                       class="no-underline hover:underline cursor-default text-indigo-darker">
                        Martin Dilling-Hansen
                    </a>
                </h1>

                <div class="block lg:flex flex-wrap justify-center">
                    <div class="w-full lg:w-1/2 bg-grey-light pl-4 lg:pl-24 pr-4 py-24">
                        <div class="w-full lg:pr-5">
                            <div class="gumroad-product-embed flex justify-center items-center"
                                 data-gumroad-product-id="mdh_simple-symmetry">
                                <a href="https://gumroad.com/l/mdh_simple-symmetry">
                                    <img src="{{ asset('images/simple-symmetry_preview.jpg') }}" alt="Simple Symmetry">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2 bg-grey-lighter lg:bg-grey-light pl-4 pr-4 lg:pr-24 py-24">
                        <div class="w-full lg:pl-5">
                            <div class="gumroad-product-embed flex justify-center items-center"
                                 data-gumroad-product-id="mdh_invasion">
                                <a href="https://gumroad.com/l/mdh_invasion">
                                    <img src="{{ asset('images/invasion_preview.jpg') }}" alt="Invasion">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block lg:flex flex-wrap justify-center">
                    <div class="w-full lg:w-1/2 bg-grey-light lg:bg-grey-lighter pl-4 lg:pl-24 pr-4 py-24">
                        <div class="w-full lg:pr-5">
                            <div class="gumroad-product-embed flex justify-center items-center"
                                 data-gumroad-product-id="mdh_crossover">
                                <a href="https://gumroad.com/l/mdh_crossover">
                                    <img src="{{ asset('images/crossover_preview.jpg') }}" alt="Crossover">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2 bg-grey-lighter pl-4 pr-4 lg:pr-24 py-24">
                        <div class="w-full lg:pl-5">
                            <div class="gumroad-product-embed flex justify-center items-center"
                                 data-gumroad-product-id="mdh_holes">
                                <a href="https://gumroad.com/l/mdh_holes">
                                    <img src="{{ asset('images/holes_preview.jpg') }}" alt="Holes">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
