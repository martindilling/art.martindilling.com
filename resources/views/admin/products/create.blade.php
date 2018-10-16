<?php
/**
 * @var \App\Product $product
 */
?>
@extends('layouts.admin')

@section('body')
    <div class="h-full font-sans">
        <div class="h-full flex justify-center">
            <div class="w-full">
                <div class="container mx-auto flex flex-wrap justify-center">
                    <div class="w-full md:w-3/4 px-4 mb-8">
                        <form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="slug">
                                    Slug
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    id="slug"
                                    name="slug"
                                    type="text"
                                    placeholder="Slug"
                                >
                            </div>
                            <div class="mb-4">
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="name">
                                    Name
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Name"
                                >
                            </div>
                            <div class="mb-4">
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="caption">
                                    Caption
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    id="caption"
                                    name="caption"
                                    type="text"
                                    placeholder="Caption"
                                >
                            </div>
                            <div class="mb-4">
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="description">
                                    Description
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    id="description"
                                    name="description"
                                    type="text"
                                    placeholder="Description"
                                >
                            </div>
                            <div class="mb-4">
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="price">
                                    Price smallest currency unit
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    id="price"
                                    name="price"
                                    type="text"
                                    placeholder="Price"
                                >
                            </div>
                            <div class="flex">
                                <div class="mb-4">
                                    <label class="block text-grey-darker text-sm font-bold mb-2" for="height">
                                        Height in cm
                                    </label>
                                    <input
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                        id="height"
                                        name="height"
                                        type="text"
                                        placeholder="Height"
                                    >
                                </div>
                                <div class="mb-4">
                                    <label class="block text-grey-darker text-sm font-bold mb-2" for="width">
                                        Width in cm
                                    </label>
                                    <input
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                        id="width"
                                        name="width"
                                        type="text"
                                        placeholder="Width"
                                    >
                                </div>
                                <div class="mb-4">
                                    <label class="block text-grey-darker text-sm font-bold mb-2" for="thickness">
                                        Thickness in cm
                                    </label>
                                    <input
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                        id="thickness"
                                        name="thickness"
                                        type="text"
                                        placeholder="Thickness"
                                    >
                                </div>
                                <div class="mb-4">
                                    <label class="block text-grey-darker text-sm font-bold mb-2" for="weight">
                                        Weight in grams
                                    </label>
                                    <input
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                        id="weight"
                                        name="weight"
                                        type="text"
                                        placeholder="Weight"
                                    >
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-grey-darker text-sm font-bold mb-2">
                                    Images
                                </label>
                                <div class="flex flex-wrap">
                                    @foreach(range(1, 8) as $index)
                                        <div class="w-1/2 flex shadow border rounded focus:outline-none focus:shadow-outline">
                                            <div class="text-xl flex justify-center items-center pl-3">
                                                {{ $index }}
                                            </div>
                                            <input
                                                class="appearance-none w-full py-2 px-3 text-grey-darker leading-tight "
                                                name="images[]"
                                                type="file"
                                            >
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <button class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                        type="submit">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
