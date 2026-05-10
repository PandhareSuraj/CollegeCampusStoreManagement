@extends('layouts.app')

@section('page_title', 'Edit Order - #' . $order->id)

@section('content')
<x-container>
    <x-page-header 
        title="Edit Order"
        subtitle="Order #{{ $order->id }}"
    />

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                <select 
                    id="vendor_id" 
                    name="vendor_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vendor_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Select a vendor</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}" @selected(old('vendor_id', $order->vendor_id) == $vendor->id)>
                            {{ $vendor->name }}
                        </option>
                    @endforeach
                </select>
                @error('vendor_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date</label>
                <input 
                    type="date" 
                    id="expected_delivery_date" 
                    name="expected_delivery_date" 
                    value="{{ old('expected_delivery_date', $order->expected_delivery_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expected_delivery_date') border-red-500 @enderror"
                    required
                >
                @error('expected_delivery_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> You can update the vendor, delivery date, and notes. Items cannot be modified here.
                </p>
            </div>

            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                >
                    Update Order
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
