@extends('layouts.app')

@section('page_title', 'User Profile - ' . $user->name)

@section('content')
<x-container>
    <x-page-header 
        title="{{ $user->name }}"
        subtitle="User ID: {{ $user->id }}"
    />

    <div class="grid grid-cols-3 gap-6">
        <!-- Left Column - User Info -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Full Name</p>
                        <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Role</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium @switch($user->role)
                            @case('teacher') bg-blue-100 text-blue-800 @break
                            @case('hod') bg-purple-100 text-purple-800 @break
                            @case('principal') bg-indigo-100 text-indigo-800 @break
                            @case('trust_head') bg-pink-100 text-pink-800 @break
                            @case('admin') bg-red-100 text-red-800 @break
                            @case('provider') bg-green-100 text-green-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Department</p>
                        <p class="text-gray-900 font-medium">{{ $user->department?->name ?? 'Not assigned' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Member Since</p>
                        <p class="text-gray-900 font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email Verified</p>
                        <p class="text-gray-900 font-medium">
                            @if ($user->email_verified_at)
                                ✓ {{ $user->email_verified_at->format('M d, Y') }}
                            @else
                                ✗ Not verified
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @if ($user->isTeacher() || $user->isHOD())
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Department Statistics</h2>
                    
                    @if ($user->isTeacher())
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600 text-sm">Total Requests</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $user->stationaryRequests()->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600 text-sm">Pending</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $user->stationaryRequests()->whereStatus('Pending')->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600 text-sm">Supplied</p>
                                <p class="text-2xl font-bold text-green-600">{{ $user->stationaryRequests()->whereStatus('Supplied')->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600 text-sm">Rejected</p>
                                <p class="text-2xl font-bold text-red-600">{{ $user->stationaryRequests()->whereStatus('Rejected')->count() }}</p>
                            </div>
                        </div>
                    @elseif ($user->isHOD())
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600 text-sm">Department Requests</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $user->department->stationaryRequests()->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600 text-sm">Teachers</p>
                                <p class="text-2xl font-bold text-purple-600">{{ $user->department->users()->where('role', 'teacher')->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600 text-sm">Pending Approvals</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $user->department->stationaryRequests()->whereStatus('Pending')->count() }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Right Column - Actions -->
        <div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Actions</h2>
                
                <div class="space-y-3">
                    @can('update', $user)
                        <a 
                            href="{{ route('users.edit', $user) }}"
                            class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                        >
                            Edit User
                        </a>
                    @endcan

                    @can('changeRole', $user)
                        <a 
                            href="{{ route('users.change-role-form', $user) }}"
                            class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium"
                        >
                            Change Role
                        </a>
                    @endcan

                    @can('assignDepartment', $user)
                        <a 
                            href="{{ route('users.assign-department-form', $user) }}"
                            class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium"
                        >
                            Assign Department
                        </a>
                    @endcan

                    @can('delete', $user)
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="block">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
                                onclick="return confirm('Are you sure?')"
                            >
                                Delete User
                            </button>
                        </form>
                    @endcan

                    <a 
                        href="{{ route('users.index') }}"
                        class="block w-full text-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium"
                    >
                        Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-container>
@endsection
