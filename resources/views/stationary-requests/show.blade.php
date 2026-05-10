@extends('layouts.app')

@section('content')
<x-container>
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $stationaryRequest->title }}</h1>
            <p class="text-gray-600 mt-2">Request #{{ $stationaryRequest->id }} • {{ $stationaryRequest->created_at->format('M d, Y') }}</p>
        </div>
        <x-status-badge status="{{ $stationaryRequest->status }}" />
    </div>

    <x-alerts />

    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Department</p>
            <p class="text-lg font-medium text-gray-900">{{ $stationaryRequest->department->name }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Requested By</p>
            <p class="text-lg font-medium text-gray-900">{{ $stationaryRequest->requestedBy->name }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Items Count</p>
            <p class="text-lg font-medium text-gray-900">{{ $stationaryRequest->items->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Description</h2>
                <p class="text-gray-700">{{ $stationaryRequest->description }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Items Requested</h2>
                <div class="space-y-3">
                    @foreach ($stationaryRequest->items as $item)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $item->notes ?? 'No notes' }}</p>
                            </div>
                            <p class="font-medium text-gray-900">{{ $item->quantity }}x</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Actions</h2>
                <div class="space-y-2">
                    @can('update', $stationaryRequest)
                        <a href="{{ route('stationary-requests.edit', $stationaryRequest) }}" class="block px-4 py-2 bg-blue-600 text-white rounded-lg text-center hover:bg-blue-700">Edit Request</a>
                    @endcan

                    @can('approve', $stationaryRequest)
                        <form action="{{ route('stationary-requests.approve', $stationaryRequest) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Approve</button>
                        </form>
                    @endcan

                    @can('reject', $stationaryRequest)
                        <form action="{{ route('stationary-requests.reject', $stationaryRequest) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="rejection_reason" value="Rejected by approver">
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Reject</button>
                        </form>
                    @endcan

                    @can('delete', $stationaryRequest)
                        <form action="{{ route('stationary-requests.destroy', $stationaryRequest) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endcan

                    <a href="{{ route('stationary-requests.index') }}" class="block px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-center hover:bg-gray-300">Back to List</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Approval History</h2>
                <div class="space-y-3">
                    @forelse ($stationaryRequest->approvals as $approval)
                        <div class="p-3 bg-gray-50 rounded">
                            <p class="font-medium text-gray-900">{{ $approval->approvedBy->name }}</p>
                            <p class="text-sm text-gray-600">{{ ucfirst($approval->status) }} • {{ $approval->created_at->format('M d, Y H:i') }}</p>
                            @if ($approval->notes)
                                <p class="text-sm text-gray-700 mt-1">{{ $approval->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No approvals yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-container>
@endsection
