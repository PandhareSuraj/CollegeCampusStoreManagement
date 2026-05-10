@extends('layouts.app')

@section('page_title', 'Add New Vendor')

@section('content')
<x-container>
    <!-- Page Header -->
    <x-page-header 
        title="Add New Vendor" 
        subtitle="Create a new vendor/supplier account"
    />

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        @if ($errors->any())
            <x-alerts type="error" :messages="$errors->all()" class="mb-6" />
        @endif

        <form action="{{ route('admin.vendors.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Vendor Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Vendor Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., ABC Suppliers" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Person -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Contact Person <span class="text-red-500">*</span>
                </label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="Mr. John Doe"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contact_person') border-red-500 @enderror" required>
                @error('contact_person')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="vendor@example.com"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 0000000000"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror" required>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror" required>
                    <option value="">Select Category</option>
                    <option value="Stationery" {{ old('category') == 'Stationery' ? 'selected' : '' }}>Stationery</option>
                    <option value="Books" {{ old('category') == 'Books' ? 'selected' : '' }}>Books</option>
                    <option value="Equipment" {{ old('category') == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                    <option value="Technology" {{ old('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                    <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>General</option>
                </select>
                @error('category')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Address <span class="text-red-500">*</span>
                </label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="Street address"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror" required>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- City & State Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        City <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="City"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror" required>
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        State <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="state" value="{{ old('state') }}" placeholder="State"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('state') border-red-500 @enderror" required>
                    @error('state')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- PIN & GST Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        PIN Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pin_code" value="{{ old('pin_code') }}" placeholder="000000"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pin_code') border-red-500 @enderror" required>
                    @error('pin_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        GST Number
                    </label>
                    <input type="text" name="gst_number" value="{{ old('gst_number') }}" placeholder="27AABCU9603R1Z0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Bank Details -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Bank Details</h3>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Account Holder Name
                        </label>
                        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Account Number
                        </label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            IFSC Code
                        </label>
                        <input type="text" name="bank_ifsc_code" value="{{ old('bank_ifsc_code') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bank Name
                        </label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="ml-2">Active</span>
                </label>
                <p class="text-xs text-gray-500">Check to activate this vendor immediately</p>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="4" placeholder="Additional notes about this vendor..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.vendors.index') }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Create Vendor
                </button>
            </div>
        </form>
    </div>
</x-container>
@endsection
