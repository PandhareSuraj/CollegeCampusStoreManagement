@extends('layouts.app')

@section('page_title', 'Approval History - ' . $stationaryRequest->title)

@section('content')
<x-container>
    <x-page-header 
        title="Approval History"
        subtitle="{{ $stationaryRequest->title }}"
    />

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Request ID</p>
            <p class="text-lg font-semibold text-gray-900">#{{ $stationaryRequest->id }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Current Status</p>
            <x-status-badge status="{{ $stationaryRequest->status }}" />
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Department</p>
            <p class="text-lg font-semibold text-gray-900">{{ $stationaryRequest->department->name }}</p>
        </div>
    </div>

    <!-- Approval Timeline -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-6">Approval Timeline</h2>
        
        @if ($approvals->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500">No approvals recorded yet</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($approvals->sortBy('created_at') as $index => $approval)
                    <div class="relative">
                        <div class="flex gap-4">
                            <!-- Timeline line -->
                            <div class="flex flex-col items-center">
                                <div class="w-4 h-4 rounded-full {{ $approval->status === 'approved' ? 'bg-green-600' : 'bg-red-600' }} mb-2"></div>
                                @if ($index < $approvals->count() - 1)
                                    <div class="w-0.5 h-20 {{ $approval->status === 'approved' ? 'bg-green-200' : 'bg-red-200' }}"></div>
                                @endif
                            </div>

                            <!-- Timeline content -->
                            <div class="flex-1 pb-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ $approval->approvedBy->name }}
                                                <span class="text-sm text-gray-500 font-normal">
                                                    ({{ ucfirst(str_replace('_', ' ', $approval->approvedBy->role)) }})
                                                </span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $approval->created_at->format('M d, Y \a\t g:i A') }}</p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            @if($approval->status === 'approved') 
                                                bg-green-100 text-green-800
                                            @else 
                                                bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($approval->status) }}
                                        </span>
                                    </div>

                                    @if ($approval->notes)
                                        <div class="mt-2 p-3 bg-white rounded border-l-4 @if($approval->status === 'approved') border-green-500 @else border-red-500 @endif">
                                            <p class="text-xs text-gray-500 font-medium uppercase">
                                                {{ $approval->status === 'approved' ? 'Approval' : 'Rejection' }} Notes:
                                            </p>
                                            <p class="text-sm text-gray-700 mt-1">{{ $approval->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Request Details -->
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Request Details</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Title</p>
                    <p class="text-gray-900 font-medium">{{ $stationaryRequest->title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Description</p>
                    <p class="text-gray-900 text-sm">{{ $stationaryRequest->description }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Requested By</p>
                    <p class="text-gray-900 font-medium">{{ $stationaryRequest->requestedBy->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Submitted</p>
                    <p class="text-gray-900 text-sm">{{ $stationaryRequest->created_at->format('M d, Y g:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Items</p>
                    <p class="text-gray-900 font-medium">{{ $stationaryRequest->items()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Items Requested</h2>
            
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse ($stationaryRequest->items as $item)
                    <div class="p-3 bg-gray-50 rounded flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->quantity }} × {{ $item->product->unit }}</p>
                        </div>
                        <span class="text-xs font-semibold text-gray-900">{{ $item->quantity }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No items in this request</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-4">
        <a 
            href="{{ route('stationary-requests.show', $stationaryRequest) }}"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
        >
            View Full Request
        </a>
        <a 
            href="{{ route('approvals.pending') }}"
            class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium"
        >
            Back to Approvals
        </a>
    </div>
</x-container>
@endsection
