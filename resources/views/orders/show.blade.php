@extends('layouts.app')

@section('page_title', 'Order Details - ' . $order->id)

@section('content')
<x-container>
    <x-page-header 
        title="Order #{{ $order->id }}"
        subtitle="Vendor: {{ $order->vendor->name }}"
    />

    <x-alerts />

    <div class="grid grid-cols-3 gap-6 mb-6">
        <!-- Order Summary -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Order Information</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Request</p>
                        <p class="text-gray-900 font-medium">{{ $order->stationaryRequest->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Vendor</p>
                        <p class="text-gray-900 font-medium">{{ $order->vendor->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Department</p>
                        <p class="text-gray-900 font-medium">{{ $order->stationaryRequest->department->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-3 py-1 rounded-full text-xs font-medium @if($order->status == 'Pending') bg-yellow-100 text-yellow-800 @elseif($order->status == 'Confirmed') bg-blue-100 text-blue-800 @elseif($order->status == 'Delivered') bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Ordered Items ({{ $order->items()->count() }})</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Product</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Unit Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Received</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($order->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->product->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->product->unit_price }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->quantity * $item->product->unit_price }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->received_quantity ?? 0 }} / {{ $item->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Delivery Tracking -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Delivery Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Expected Delivery Date</p>
                        <p class="text-lg font-medium text-gray-900">{{ $order->expected_delivery_date?->format('M d, Y') ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Delivery Status</p>
                        <span class="px-3 py-1 rounded-full text-xs font-medium @if($order->delivery_status == 'Pending') bg-yellow-100 text-yellow-800 @elseif($order->delivery_status == 'In_Transit') bg-blue-100 text-blue-800 @elseif($order->delivery_status == 'Delivered') bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ str_replace('_', ' ', $order->delivery_status ?? 'Pending') }}
                        </span>
                    </div>
                    @if ($order->notes)
                        <div>
                            <p class="text-sm text-gray-600">Notes</p>
                            <p class="text-gray-900">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Actions -->
        <div>
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Actions</h2>
                
                <div class="space-y-3">
                    @can('confirm', $order)
                        <form action="{{ route('orders.confirm', $order) }}" method="POST" class="block">
                            @csrf
                            <button 
                                type="submit"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm"
                            >
                                Confirm Order
                            </button>
                        </form>
                    @endcan

                    @can('updateDeliveryStatus', $order)
                        <a 
                            href="{{ route('orders.update-delivery-status', $order) }}"
                            class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm"
                        >
                            Update Delivery
                        </a>
                    @endcan

                    @can('update', $order)
                        <a 
                            href="{{ route('orders.edit', $order) }}"
                            class="block w-full text-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium text-sm"
                        >
                            Edit Order
                        </a>
                    @endcan

                    @can('receiveItems', $order)
                        <a 
                            href="{{ route('orders.receive-form', $order) }}"
                            class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium text-sm"
                        >
                            Receive Items
                        </a>
                    @endcan

                    @can('delete', $order)
                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="block">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm"
                                onclick="return confirm('Delete this order?')"
                            >
                                Delete Order
                            </button>
                        </form>
                    @endcan

                    <a 
                        href="{{ route('orders.index') }}"
                        class="block w-full text-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium text-sm"
                    >
                        Back to Orders
                    </a>
                </div>

                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-600">Order Created</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-container>
@endsection
