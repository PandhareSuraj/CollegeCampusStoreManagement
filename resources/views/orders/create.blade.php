@extends('layouts.app')

@section('content')
<x-container>
    <x-page-header title="Create Order" subtitle="Create a new order from an approved request" />

    <x-alerts />

    <form action="{{ route('orders.store') }}" method="POST" class="bg-white rounded-lg shadow p-8">
        @csrf

        <div class="mb-6">
            <label for="stationary_request_id" class="block text-sm font-medium text-gray-700 mb-2">Stationary Request</label>
            <select name="stationary_request_id" id="stationary_request_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stationary_request_id') border-red-500 @enderror" required>
                <option value="">Select Request</option>
                @foreach ($stationaryRequests as $request)
                    <option value="{{ $request->id }}">{{ $request->title }} ({{ $request->department->name }})</option>
                @endforeach
            </select>
            @error('stationary_request_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
            <select name="vendor_id" id="vendor_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vendor_id') border-red-500 @enderror" required>
                <option value="">Select Vendor</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                @endforeach
            </select>
            @error('vendor_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date</label>
            <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('expected_delivery_date') border-red-500 @enderror" required>
            @error('expected_delivery_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Order</button>
            <a href="{{ route('orders.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</x-container>
@endsection
