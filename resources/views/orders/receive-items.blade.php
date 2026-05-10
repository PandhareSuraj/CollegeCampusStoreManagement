@extends('layouts.app')

@section('page_title', 'Receive Items - Order #' . $order->id)

@section('content')
<x-container>
    <x-page-header 
        title="Receive Order Items"
        subtitle="Order #{{ $order->id }} - {{ $order->stationaryRequest->title }}"
    />

    <x-alerts />

    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
            
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Vendor</p>
                    <p class="text-gray-900 font-medium">{{ $order->vendor->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Expected Date</p>
                    <p class="text-gray-900 font-medium">{{ $order->expected_delivery_date?->format('M d, Y') ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Items</p>
                    <p class="text-gray-900 font-medium">{{ $order->items->count() }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('orders.receive-items', $order) }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('POST')

            <h2 class="text-lg font-medium text-gray-900 mb-4">Record Received Items</h2>

            <div class="space-y-4 mb-6">
                @foreach ($order->items as $index => $item)
                    <div class="border border-gray-300 rounded-lg p-4">
                        <div class="grid grid-cols-4 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Product</p>
                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Unit</p>
                                <p class="font-medium text-gray-900">{{ $item->product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Ordered Qty</p>
                                <p class="font-medium text-gray-900">{{ $item->quantity }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Previously Received</p>
                                <p class="font-medium text-gray-900">{{ $item->received_quantity ?? 0 }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="received_quantity_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantity Received
                                </label>
                                <input 
                                    type="number" 
                                    id="received_quantity_{{ $index }}" 
                                    name="items[{{ $index }}][received_quantity]"
                                    value="{{ old('items.' . $index . '.received_quantity', 0) }}"
                                    min="0"
                                    max="{{ $item->quantity }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('items.' . $index . '.received_quantity') border-red-500 @enderror"
                                    required
                                >
                                @error('items.' . $index . '.received_quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="condition_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Condition
                                </label>
                                <select 
                                    id="condition_{{ $index }}" 
                                    name="items[{{ $index }}][condition]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="Good" @selected(old('items.' . $index . '.condition') === 'Good')>Good</option>
                                    <option value="Acceptable" @selected(old('items.' . $index . '.condition') === 'Acceptable')>Acceptable</option>
                                    <option value="Damaged" @selected(old('items.' . $index . '.condition') === 'Damaged')>Damaged</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="notes_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea 
                                id="notes_{{ $index }}" 
                                name="items[{{ $index }}][notes]"
                                rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="Add any notes about this item's delivery..."
                            >{{ old('items.' . $index . '.notes') }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>ℹ️ Instructions:</strong> Enter the quantity received for each item and note its condition. You can record partial deliveries.
                </p>
            </div>

            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
                >
                    Record Receipt
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
</x-container>
@endsection
