@extends('layouts.app')

@section('page_title', 'Pending Approvals')

@section('content')
<x-container>
    <x-page-header 
        title="Pending Approvals"
        subtitle="Approvals waiting for your action"
    />

    <x-alerts />

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Total Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $pendingApprovals->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Created Today</p>
            <p class="text-3xl font-bold text-blue-600">{{ $pendingApprovals->where('created_at', '>=', now()->startOfDay())->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Oldest</p>
            <p class="text-sm font-semibold text-gray-900">{{ $pendingApprovals->sortBy('created_at')->first()?->created_at?->diffForHumans() ?? 'None' }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Average Dept</p>
            <p class="text-lg font-semibold text-purple-600">
                {{ $pendingApprovals->groupBy('stationaryRequest.department_id')->count() }}
                {{ $pendingApprovals->groupBy('stationaryRequest.department_id')->count() === 1 ? 'dept' : 'depts' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if ($pendingApprovals->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No pending approvals</h3>
                <p class="mt-1 text-sm text-gray-500">All approvals have been processed. Great work!</p>
            </div>
        @else
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Request</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Requested By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Current Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Submitted</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($pendingApprovals as $approval)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $approval->stationaryRequest->title }}</p>
                                <p class="text-xs text-gray-500">ID: {{ $approval->stationaryRequest->id }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $approval->stationaryRequest->department->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $approval->stationaryRequest->requestedBy->name }}
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge status="{{ $approval->stationaryRequest->status }}" />
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $approval->stationaryRequest->created_at->format('M d, Y') }}
                                <p class="text-xs text-gray-500">{{ $approval->stationaryRequest->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a 
                                    href="{{ route('stationary-requests.show', $approval->stationaryRequest) }}"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium inline-block"
                                >
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-blue-800">
            <strong>💡 Tip:</strong> Click "Review" to view the full request details, items list, and approval options.
        </p>
    </div>
</x-container>
@endsection
