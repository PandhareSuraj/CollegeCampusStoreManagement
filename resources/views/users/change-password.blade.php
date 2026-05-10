@extends('layouts.app')

@section('page_title', 'Change Password')

@section('content')
<x-container>
    <x-page-header 
        title="Change Password"
        subtitle="Update your account password"
    />

    <x-alerts />

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-2">Password Requirements</h2>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✓ Minimum 8 characters</li>
                    <li>✓ Mix of uppercase and lowercase letters</li>
                    <li>✓ At least one number</li>
                    <li>✓ At least one special character</li>
                </ul>
            </div>

            <form action="{{ route('users.change-password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                        required
                    >
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
                    >
                        Change Password
                    </button>
                    <a 
                        href="{{ route('users.profile') }}" 
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
