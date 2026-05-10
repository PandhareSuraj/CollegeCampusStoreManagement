@extends('layouts.app')

@section('content')
<x-container>
    <x-page-header title="Stationary Requests">
        <x-slot name="actions">
            @can('create', App\Models\StationaryRequest::class)
                <x-button href="{{ route('stationary-requests.create') }}" label="+ New Request" variant="primary" />
            @endcan
        </x-slot>
    </x-page-header>

    <x-alerts />

    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Department</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Requested By</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Created</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $request->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $request->department->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $request->requestedBy->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <x-status-badge status="{{ $request->status }}" />
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('stationary-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No requests found. {{ auth()->user()->isTeacher() ? 'Create your first request!' : '' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $requests->links() }}
    </div>
</x-container>
@endsection
