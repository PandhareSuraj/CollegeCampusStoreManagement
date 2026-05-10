@extends('layouts.app')

@section('page_title', 'Completed Approvals')

@section('content')
<x-container>
    <x-page-header 
        title="Completed Approvals"
        subtitle="Approvals you have processed"
    />

    <x-alerts />

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Total Processed</p>
            <p class="text-3xl font-bold text-blue-600">{{ $completedApprovals->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Approved</p>
            <p class="text-3xl font-bold text-green-600">{{ $completedApprovals->where('status', 'approved')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Rejected</p>
            <p class="text-3xl font-bold text-red-600">{{ $completedApprovals->where('status', 'rejected')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Approval Rate</p>
            <p class="text-3xl font-bold text-purple-600">
                {{ $completedApprovals->count() > 0 ? round(($completedApprovals->where('status', 'approved')->count() / $completedApprovals->count()) * 100) : 0 }}%
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if ($completedApprovals->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No completed approvals</h3>
                <p class="mt-1 text-sm text-gray-500">You haven't processed any approvals yet.</p>
            </div>
        @else
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Request</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Approved By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Processed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Notes</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($completedApprovals->sortByDesc('created_at') as $approval)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $approval->stationaryRequest->title }}</p>
                                <p class="text-xs text-gray-500">ID: {{ $approval->stationaryRequest->id }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $approval->stationaryRequest->department->name }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($approval->status === 'approved')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">✓ Approved</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">✗ Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $approval->approvedBy->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $approval->created_at->format('M d, Y') }}
                                <p class="text-xs text-gray-500">{{ $approval->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $approval->notes ? substr($approval->notes, 0, 50) . '...' : '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a 
                                    href="{{ route('stationary-requests.show', $approval->stationaryRequest) }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium inline-block"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-container>
@endsection
