<?php
/**
 * @var \Stripe\SKU[]|Illuminate\Support\Collection $skus
 * @var \App\Order[]|Illuminate\Support\Collection $orders
 * @var \App\Order $order
 */
?>
@extends('layouts.admin')

@section('body')
    <div class="h-full font-sans">
        <div class="h-full flex justify-center">
            <div class="w-full">
                <div class="container mx-auto flex flex-wrap">
                    @forelse($orders as $status => $statusOrders)
                        <div class="w-full px-4 mb-4 text-3xl font-bold border-b-2 mt-6">
                            {{ ucfirst($status) }}
                        </div>
                        @forelse($statusOrders as $order)
                            <div class="w-full px-4 mb-8">
                                <div class="block relative p-1 border shadow no-underline bg-grey-lightest">
                                    <div class="flex px-3 pt-2 pb-3">
                                        <div class="">
                                            @foreach($order->skuItems() as $skuItem)
                                                <img class="w-64" src="{{ $skus[$skuItem->parent]->image }}" alt="{{ $skuItem->description }}">
                                            @endforeach
                                        </div>

                                        <div class="w-32 mx-6">
                                            <div class="flex flex-col text-sm text-grey-darker" title="{{ $order->created->toDateTimeString() }}">
                                                <div class="bg-white rounded text-center shadow overflow-hidden">
                                                    <div class="text-2xl font-bold text-grey-darkest py-1 rounded-t border border-b-0 border-grey">
                                                        {{ $order->created->day }}
                                                    </div>
                                                    <div class="text-sm bg-red text-white font-bold px-2 pt-1">
                                                        {{ $order->created->format('F') }}
                                                    </div>
                                                    <div class="bg-red text-white px-2 pb-1">
                                                        {{ $order->created->format('Y') }}
                                                    </div>
                                                    <div class="bg-grey-darker text-white px-2 py-1 rounded-b">
                                                        {{ $order->created->format('H:i:s') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="border-b mt-4">

                                            <div class="flex flex-col text-sm text-grey-darker">
                                                @foreach($order->status_transitions->__toArray() as $stat => $timestamp)
                                                    <div class="{{ $timestamp ? 'text-grey-darkest' : 'text-grey' }} mt-2">
                                                        {{ ucfirst($stat) }}
                                                    </div>
                                                    @if($timestamp)
                                                        <div class="text-xs">
                                                            {{ v\time($timestamp) }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-sm text-grey-darker">
                                                <div class="mb-2 text-grey-darkest">
                                                    {{ $order->shipping->name }}
                                                </div>
                                                <div class="mb-2">
                                                    {{ $order->email }}
                                                </div>
                                                <div class="rounded border border-grey-light bg-grey-lightest px-3 py-2">
                                                    {!! v\address($order->shipping->address) !!}<br>
                                                    <small>
                                                        State: {{ $order->shipping->address->state }}
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="text-sm text-grey-dark mt-4">
                                                @foreach($order->items as $item)
                                                    <div>
                                                        {{ $item->quantity ?? '0' }} x
                                                        {{ $item->description }} =
                                                        {{ v\price($item->amount) }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="flex-1">

                                        </div>

                                        <div class="w-48 text-sm text-grey-dark ml-3">
                                            @if('created' === $order->status)
                                                <a
                                                    href="{{ route('admin.orders.mark-shipped', ['id' => $order->id]) }}" onclick="event.preventDefault();document.getElementById('shipped-{{ $order->id }}').submit();"
                                                    class="w-full no-underline float-right text-center bg-blue hover:bg-blue-light text-white font-bold py-2 px-4 border-b-4 border-blue-dark hover:border-blue rounded"
                                                >
                                                    {!! __('Mark as shipped <br>and charge customer') !!}
                                                </a>
                                                <form id="shipped-{{ $order->id }}" action="{{ route('admin.orders.mark-shipped', ['id' => $order->id]) }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            @endif
                                            @if('paid' === $order->status)
                                                <a
                                                    href="{{ route('admin.orders.mark-fulfilled', ['id' => $order->id]) }}" onclick="event.preventDefault();document.getElementById('fulfilled-{{ $order->id }}').submit();"
                                                    class="w-full no-underline float-right text-center bg-blue hover:bg-blue-light text-white font-bold py-2 px-4 border-b-4 border-blue-dark hover:border-blue rounded"
                                                >
                                                    {{ __('Mark as fulfilled') }}
                                                </a>
                                                <form id="fulfilled-{{ $order->id }}" action="{{ route('admin.orders.mark-fulfilled', ['id' => $order->id]) }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            @endif
                                        </div>
                                        {{--"object": "order_item",--}}
                                        {{--"amount": 60000,--}}
                                        {{--"currency": "dkk",--}}
                                        {{--"description": "Simple Symmetry",--}}
                                        {{--"parent": "sku_DRPtjb5QTWQ54I",--}}
                                        {{--"quantity": 1,--}}
                                        {{--"type": "sku"--}}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-full py-32 cursor-default text-3xl text-center text-grey">
                                No orders found
                            </div>
                        @endforelse
                    @empty
                        <div class="w-full py-32 cursor-default text-3xl text-center text-grey">
                            No orders found
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
@endsection
