@extends('layouts.app')

@section('page_title', 'My Profile')

@section('content')
<x-container>
    <x-page-header 
        title="My Profile"
        subtitle="Manage your account settings"
    />

    <x-alerts />

    <div class="grid grid-cols-3 gap-6">
        <!-- Left Column - Profile Info -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h2>
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Full Name</p>
                        <p class="text-lg font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-lg font-medium text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Role</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium @switch(auth()->user()->role)
                            @case('teacher') bg-blue-100 text-blue-800 @break
                            @case('hod') bg-purple-100 text-purple-800 @break
                            @case('principal') bg-indigo-100 text-indigo-800 @break
                            @case('trust_head') bg-pink-100 text-pink-800 @break
                            @case('admin') bg-red-100 text-red-800 @break
                            @case('provider') bg-green-100 text-green-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Department</p>
                        <p class="text-lg font-medium text-gray-900">{{ auth()->user()->department?->name ?? 'Not assigned' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email Verified</p>
                        <p class="text-lg font-medium text-gray-900">
                            @if (auth()->user()->email_verified_at)
                                ✓ Yes
                            @else
                                ✗ No
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Member Since</p>
                        <p class="text-lg font-medium text-gray-900">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Section -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Update Profile</h2>
                
                <form action="{{ route('users.update-profile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', auth()->user()->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            required
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', auth()->user()->email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                    >
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Change Password</h2>
                
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

                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
                    >
                        Change Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column - Quick Actions -->
        <div>
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Info</h2>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-xs text-blue-600 font-medium uppercase">Your Role</p>
                        <p class="text-lg font-semibold text-blue-900 mt-1">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                        <p class="text-xs text-blue-700 mt-2">{{ match(auth()->user()->role) {
                            'teacher' => 'Submit stationary requests',
                            'hod' => 'Approve requests from your department',
                            'principal' => 'Review HOD approvals',
                            'trust_head' => 'Review Principal approvals',
                            'admin' => 'System administration access',
                            'provider' => 'Manage orders and deliveries',
                            default => 'User permissions'
                        } }}</p>
                    </div>

                    @if (auth()->user()->department)
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-xs text-purple-600 font-medium uppercase">Your Department</p>
                            <p class="text-lg font-semibold text-purple-900 mt-1">{{ auth()->user()->department->name }}</p>
                            <p class="text-xs text-purple-700 mt-2">Contact your admin to change</p>
                        </div>
                    @endif

                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-xs text-green-600 font-medium uppercase">Account Status</p>
                        <p class="text-lg font-semibold text-green-900 mt-1">✓ Active</p>
                        <p class="text-xs text-green-700 mt-2">Since {{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-container>
@endsection
