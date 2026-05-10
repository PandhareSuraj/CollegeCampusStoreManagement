@extends('layouts.app')

@section('page_title', 'Update Delivery - Order #' . $order->id)

@section('content')
<x-container>
    <x-page-header 
        title="Update Delivery Status"
        subtitle="Order #{{ $order->id }} - {{ $order->vendor->name }}"
    />

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Current Status</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Request</p>
                    <p class="text-gray-900 font-medium">{{ $order->stationaryRequest->title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Current Delivery Status</p>
                    <span class="px-3 py-1 rounded-full text-xs font-medium @if($order->delivery_status == 'Pending') bg-yellow-100 text-yellow-800 @elseif($order->delivery_status == 'In_Transit') bg-blue-100 text-blue-800 @elseif($order->delivery_status == 'Delivered') bg-green-100 text-green-800 @elseif($order->delivery_status == 'Delayed') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                        {{ str_replace('_', ' ', $order->delivery_status ?? 'Pending') }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Expected Delivery</p>
                    <p class="text-gray-900 font-medium">{{ $order->expected_delivery_date?->format('M d, Y') ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Items Ordered</p>
                    <p class="text-gray-900 font-medium">{{ $order->items->count() }} items</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('orders.update-delivery-status', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="delivery_status" class="block text-sm font-medium text-gray-700 mb-2">Delivery Status</label>
                    <select 
                        id="delivery_status" 
                        name="delivery_status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('delivery_status') border-red-500 @enderror"
                        required
                    >
                        <option value="Pending" @selected($order->delivery_status === 'Pending')>Pending - Not yet shipped</option>
                        <option value="In_Transit" @selected($order->delivery_status === 'In_Transit')>In Transit - On the way</option>
                        <option value="Delivered" @selected($order->delivery_status === 'Delivered')>Delivered - Arrived</option>
                        <option value="Delayed" @selected($order->delivery_status === 'Delayed')>Delayed - Late delivery</option>
                    </select>
                    @error('delivery_status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Delivery Notes</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Add delivery status updates or issues..."
                    >{{ old('notes', $order->notes) }}</textarea>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800 mb-2">
                        <strong>Status Guide:</strong>
                    </p>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• <strong>Pending:</strong> Order not yet shipped from vendor</li>
                        <li>• <strong>In Transit:</strong> Order is on its way to institution</li>
                        <li>• <strong>Delivered:</strong> Order has arrived and items received</li>
                        <li>• <strong>Delayed:</strong> Order is late and needs follow-up</li>
                    </ul>
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                    >
                        Update Status
                    </button>
                    <a 
                        href="{{ route('orders.show', $order) }}" 
                        class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-container>
@endsection
