@extends('layouts.app')

@section('content')
<x-container>
    <x-page-header title="Create Stationary Request" subtitle="Submit a new request for stationary items" />

    <x-alerts />

    <form action="{{ route('stationary-requests.store') }}" method="POST" class="bg-white rounded-lg shadow p-8">
        @csrf

        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Request Title</label>
            <input type="text" name="title" id="title" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror" value="{{ old('title') }}" required>
            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
            <select name="department_id" id="department_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('department_id') border-red-500 @enderror" required>
                <option value="">Select Department</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
            @error('department_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Items</h3>
            <div id="items-container" class="space-y-4">
                <div class="item-row border rounded-lg p-4 bg-gray-50">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                            <select name="items[0][product_id]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <input type="number" name="items[0][quantity]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="10000" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <input type="text" name="items[0][notes]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Optional notes">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addItemRow()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">+ Add Item</button>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Submit Request</button>
            <a href="{{ route('stationary-requests.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</x-container>

<script>
function addItemRow() {
    const container = document.getElementById('items-container');
    const rowCount = container.querySelectorAll('.item-row').length;
    const newRow = document.querySelector('.item-row').cloneNode(true);
    
    // Update all input names
    newRow.querySelectorAll('input, select').forEach(el => {
        const name = el.name.replace(/\[\d+\]/, `[${rowCount}]`);
        el.name = name;
        el.value = '';
    });
    
    container.appendChild(newRow);
}
</script>
@endsection
