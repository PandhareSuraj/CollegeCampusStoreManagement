@extends('layouts.app')

@section('page_title', 'Reports & Analytics')

@section('content')
<x-container>
    <!-- Page Header -->
    <x-page-header 
        title="Reports & Analytics" 
        subtitle="System insights and performance metrics"
    />

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Requests -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $metrics['total_requests'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                <span class="text-green-600 font-medium">↑ 12%</span> from last month
            </p>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $metrics['total_orders'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-box text-green-600 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                <span class="text-green-600 font-medium">↑ 8%</span> from last month
            </p>
        </div>

        <!-- Total Amount -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Amount Spent</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">₹{{ number_format($metrics['total_amount'] ?? 0) }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-rupee-sign text-yellow-600 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                <span class="text-green-600 font-medium">↑ 15%</span> from last month
            </p>
        </div>

        <!-- Approval Rate -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Approval Rate</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $metrics['approval_rate'] ?? 0 }}%</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                <span class="text-green-600 font-medium">↑ 2%</span> from last month
            </p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Request Status Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Status Distribution</h3>
            
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Pending</span>
                        <span class="text-sm font-medium text-gray-900">{{ $statusDistribution['pending'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $statusDistribution['pending'] ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">In Approval</span>
                        <span class="text-sm font-medium text-gray-900">{{ $statusDistribution['in_approval'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $statusDistribution['in_approval'] ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Approved</span>
                        <span class="text-sm font-medium text-gray-900">{{ $statusDistribution['approved'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $statusDistribution['approved'] ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Rejected</span>
                        <span class="text-sm font-medium text-gray-900">{{ $statusDistribution['rejected'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $statusDistribution['rejected'] ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Supplied</span>
                        <span class="text-sm font-medium text-gray-900">{{ $statusDistribution['supplied'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $statusDistribution['supplied'] ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests by Department -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Departments by Request Volume</h3>
            
            <div class="space-y-3">
                @forelse($topDepartments ?? [] as $dept => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 flex-1">{{ $dept }}</span>
                        <span class="text-sm font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full">{{ $count }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Approval Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Average Approval Time -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Average Approval Time (days)</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                    <span class="text-gray-700">HOD Level</span>
                    <span class="text-lg font-bold text-blue-600">{{ $approvalTimes['hod'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded">
                    <span class="text-gray-700">Principal Level</span>
                    <span class="text-lg font-bold text-purple-600">{{ $approvalTimes['principal'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded">
                    <span class="text-gray-700">TrustHead Level</span>
                    <span class="text-lg font-bold text-orange-600">{{ $approvalTimes['trust_head'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Top Approvers -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Approvers</h3>
            
            <div class="space-y-2">
                @forelse($topApprovers ?? [] as $approver)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-medium">
                                {{ substr($approver['name'] ?? 'A', 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $approver['name'] ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $approver['role'] ?? 'Approver' }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">{{ $approver['count'] ?? 0 }} approvals</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Export Reports -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Reports</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.reports.export', ['format' => 'pdf']) }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-red-50 transition">
                <i class="fas fa-file-pdf text-red-500 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 font-medium">Export as PDF</span>
            </a>

            <a href="{{ route('admin.reports.export', ['format' => 'csv']) }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 transition">
                <i class="fas fa-file-csv text-green-500 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 font-medium">Export as CSV</span>
            </a>

            <a href="{{ route('admin.reports.export', ['format' => 'excel']) }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition">
                <i class="fas fa-file-excel text-blue-500 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 font-medium">Export as Excel</span>
            </a>

            <button onclick="window.print()" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 transition">
                <i class="fas fa-print text-purple-500 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 font-medium">Print Report</span>
            </button>
        </div>
    </div>
</x-container>
@endsection
