@extends('layouts.app')

@section('page_title', 'Change Role - ' . $user->name)

@section('content')
<x-container>
    <x-page-header 
        title="Change User Role"
        subtitle="User: {{ $user->name }} | Current Role: {{ ucfirst($user->role) }}"
    />

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">User Information</h2>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Current Role</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Department</p>
                    <p class="text-gray-900 font-medium">{{ $user->department?->name ?? 'Not assigned' }}</p>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <strong>⚠️ Important:</strong> Changing a user's role may affect their permissions and access to the system. They will have immediate access with their new role.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('users.change-role', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">New Role</label>
                    <select 
                        id="role" 
                        name="role"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a role</option>
                        @foreach (App\Enums\UserRole::cases() as $roleCase)
                            <option value="{{ $roleCase->value }}" @selected(old('role', $user->role) === $roleCase->value)>
                                {{ $roleCase->label() }} - {{ $roleCase->description() }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Reason for Change (Optional)</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Document why this role change is being made..."
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium"
                    >
                        Change Role
                    </button>
                    <a 
                        href="{{ route('users.show', $user) }}" 
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
