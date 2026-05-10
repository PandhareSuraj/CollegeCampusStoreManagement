@extends('layouts.app')

@section('content')
<x-container>
    <x-page-header title="Users">
        <x-slot name="actions">
            @can('create', App\Models\User::class)
                <x-button href="{{ route('users.create') }}" label="+ New User" variant="primary" />
            @endcan
        </x-slot>
    </x-page-header>

    <x-alerts />

    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Department</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Created</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->department?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-800">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-container>
@endsection
