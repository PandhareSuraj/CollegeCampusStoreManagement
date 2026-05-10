@extends('layouts.app')

@section('page_title', 'Track Delivery - Order #' . $order->id)

@section('content')
<x-container>
    <x-page-header 
        title="Delivery Tracking"
        subtitle="Order #{{ $order->id }}"
    />

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Request</p>
            <p class="text-gray-900 font-semibold">{{ $order->stationaryRequest->title }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Vendor</p>
            <p class="text-gray-900 font-semibold">{{ $order->vendor->name }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Status</p>
            <span class="px-3 py-1 rounded-full text-xs font-medium @if($order->delivery_status == 'Pending') bg-yellow-100 text-yellow-800 @elseif($order->delivery_status == 'In_Transit') bg-blue-100 text-blue-800 @elseif($order->delivery_status == 'Delivered') bg-green-100 text-green-800 @elseif($order->delivery_status == 'Delayed') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                {{ str_replace('_', ' ', $order->delivery_status ?? 'Pending') }}
            </span>
        </div>
    </div>

    <!-- Delivery Timeline -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-6">Delivery Timeline</h2>
        
        <div class="space-y-8">
            <!-- Order Created -->
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-4 h-4 rounded-full bg-green-600 mb-2"></div>
                    <div class="w-0.5 h-16 bg-green-200"></div>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Order Created</p>
                    <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>

            <!-- Pending -->
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-4 h-4 rounded-full @if(in_array($order->delivery_status, ['Pending', 'In_Transit', 'Delivered'])) bg-green-600 @else bg-yellow-600 @endif mb-2"></div>
                    @if(!($order->delivery_status === 'Pending' && $order->updated_at == $order->created_at))
                        <div class="w-0.5 h-16 @if(in_array($order->delivery_status, ['In_Transit', 'Delivered'])) bg-green-200 @else bg-yellow-200 @endif"></div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Pending</p>
                    <p class="text-sm text-gray-600">
                        @if($order->delivery_status === 'Pending')
                            Currently waiting to be shipped
                        @else
                            Shipped on {{ $order->updated_at->format('M d, Y') }}
                        @endif
                    </p>
                </div>
            </div>

            <!-- In Transit -->
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-4 h-4 rounded-full @if(in_array($order->delivery_status, ['In_Transit', 'Delivered'])) bg-green-600 @else bg-gray-300 @endif mb-2"></div>
                    @if(in_array($order->delivery_status, ['Delivered']))
                        <div class="w-0.5 h-16 bg-green-200"></div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">In Transit</p>
                    <p class="text-sm text-gray-600">
                        @if(in_array($order->delivery_status, ['In_Transit', 'Delivered']))
                            On its way to {{ $order->stationaryRequest->department->name }}
                        @else
                            Awaiting shipment
                        @endif
                    </p>
                </div>
            </div>

            <!-- Delivery -->
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-4 h-4 rounded-full @if($order->delivery_status === 'Delivered') bg-green-600 @else bg-gray-300 @endif mb-2"></div>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Delivered</p>
                    <p class="text-sm text-gray-600">
                        @if($order->delivery_status === 'Delivered')
                            Delivered on {{ $order->delivered_at?->format('M d, Y \a\t g:i A') ?? 'Recently' }}
                        @else
                            Expected by {{ $order->expected_delivery_date?->format('M d, Y') ?? 'TBD' }}
                        @endif
                    </p>
                </div>
            </div>

            <!-- Delayed Status -->
            @if($order->delivery_status === 'Delayed')
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full bg-red-600 mb-2"></div>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 text-red-600">⚠️ Delayed</p>
                        <p class="text-sm text-gray-600">Late beyond expected delivery date</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Items Tracking -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Items Status</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700">Ordered</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700">Received</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700">Pending</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($order->items as $item)
                        @php
                            $received = $item->received_quantity ?? 0;
                            $pending = $item->quantity - $received;
                            $status = $received === $item->quantity ? 'Completed' : ($received > 0 ? 'Partial' : 'Pending');
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->product->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-green-600">{{ $received }}</td>
                            <td class="px-4 py-3 text-sm text-orange-600">{{ $pending }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium 
                                    @if($status === 'Completed') bg-green-100 text-green-800
                                    @elseif($status === 'Partial') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Overall Progress -->
        <div class="mt-6 pt-6 border-t">
            <p class="text-sm font-medium text-gray-900 mb-2">Overall Delivery Progress</p>
            @php
                $totalOrdered = $order->items->sum('quantity');
                $totalReceived = $order->items->sum('received_quantity') ?? 0;
                $progressPercent = $totalOrdered > 0 ? ($totalReceived / $totalOrdered) * 100 : 0;
            @endphp
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-green-600 h-3 rounded-full" style="width: {{ $progressPercent }}%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-2">{{ $totalReceived }} of {{ $totalOrdered }} items received ({{ round($progressPercent) }}%)</p>
        </div>
    </div>

    <div class="mt-6 flex gap-4">
        <a 
            href="{{ route('orders.show', $order) }}"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
        >
            View Order
        </a>
        <a 
            href="{{ route('orders.index') }}"
            class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium"
        >
            Back to Orders
        </a>
    </div>
</x-container>
@endsection
