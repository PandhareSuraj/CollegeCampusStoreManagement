@extends('layouts.app')

@section('page_title', 'Approval Statistics')

@section('content')
<x-container>
    <x-page-header 
        title="Approval Statistics"
        subtitle="System-wide approval metrics"
    />

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm uppercase">Total Approvals</p>
            <p class="text-4xl font-bold text-blue-600 mt-2">{{ $stats['total'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">All-time approvals processed</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm uppercase">Approved</p>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ $stats['approved'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100) : 0 }}% approval rate</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm uppercase">Rejected</p>
            <p class="text-4xl font-bold text-red-600 mt-2">{{ $stats['rejected'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $stats['total'] > 0 ? round(($stats['rejected'] / $stats['total']) * 100) : 0 }}% rejection rate</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm uppercase">Pending</p>
            <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $stats['pending'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Awaiting action</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <!-- Approvals by Level -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Approvals by Level</h2>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-medium text-gray-900">HOD Level</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $stats['hod_approved'] ?? 0 }}</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? (($stats['hod_approved'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-medium text-gray-900">Principal Level</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $stats['principal_approved'] ?? 0 }}</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? (($stats['principal_approved'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-medium text-gray-900">TrustHead Level</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $stats['trust_approved'] ?? 0 }}</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? (($stats['trust_approved'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Time -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Approval Response Times</h2>
            
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 font-medium">Average Time to Approve</p>
                    <p class="text-2xl font-bold text-blue-900 mt-1">{{ $stats['avg_approval_time'] ?? 'N/A' }}</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-600 font-medium">Fastest Approval</p>
                    <p class="text-lg font-semibold text-green-900 mt-1">{{ $stats['fastest_approval'] ?? 'N/A' }}</p>
                </div>

                <div class="bg-orange-50 p-4 rounded-lg">
                    <p class="text-sm text-orange-600 font-medium">Slowest Approval</p>
                    <p class="text-lg font-semibold text-orange-900 mt-1">{{ $stats['slowest_approval'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Approvers -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Top Approvers</h2>
        
        @if ($topApprovers->isEmpty())
            <p class="text-gray-500 text-sm">No approval data available yet</p>
        @else
            <div class="space-y-3">
                @foreach ($topApprovers as $approver)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                                {{ substr($approver->approvedBy->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $approver->approvedBy->name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $approver->approvedBy->role)) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ $approver->total_approvals }}</p>
                            <p class="text-xs text-gray-500">approvals</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-container>
@endsection
