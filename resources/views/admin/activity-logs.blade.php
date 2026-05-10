@extends('layouts.app')

@section('page_title', 'Activity Logs')

@section('content')
<x-container>
    <!-- Page Header -->
    <x-page-header 
        title="Activity Logs" 
        subtitle="System-wide activity tracking"
    />

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.activity-logs') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- User Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Users</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Actions</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    <option value="viewed" {{ request('action') == 'viewed' ? 'selected' : '' }}>Viewed</option>
                    <option value="approved" {{ request('action') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('action') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Resource Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Resource</label>
                <select name="resource_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Resources</option>
                    <option value="User" {{ request('resource_type') == 'User' ? 'selected' : '' }}>User</option>
                    <option value="StationaryRequest" {{ request('resource_type') == 'StationaryRequest' ? 'selected' : '' }}>Request</option>
                    <option value="Order" {{ request('resource_type') == 'Order' ? 'selected' : '' }}>Order</option>
                    <option value="Approval" {{ request('resource_type') == 'Approval' ? 'selected' : '' }}>Approval</option>
                    <option value="Department" {{ request('resource_type') == 'Department' ? 'selected' : '' }}>Department</option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
                <a href="{{ route('admin.activity-logs') }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Activity Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Total Activities</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Today</p>
            <p class="text-2xl font-bold text-blue-600">{{ $statistics['today'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">This Week</p>
            <p class="text-2xl font-bold text-green-600">{{ $statistics['this_week'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">This Month</p>
            <p class="text-2xl font-bold text-purple-600">{{ $statistics['this_month'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Activity Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($activities ?? [] as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-medium">
                                        {{ substr($activity->user?->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity->user?->name ?? 'System' }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity->user?->email ?? 'system@campus.edu' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge 
                                    :status="$activity->action"
                                    :colors="[
                                        'created' => 'bg-green-100 text-green-800',
                                        'updated' => 'bg-blue-100 text-blue-800',
                                        'deleted' => 'bg-red-100 text-red-800',
                                        'viewed' => 'bg-gray-100 text-gray-800',
                                        'approved' => 'bg-purple-100 text-purple-800',
                                        'rejected' => 'bg-orange-100 text-orange-800',
                                    ]"
                                />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $activity->resource_type }}</span>
                                <p class="text-xs text-gray-500">ID: {{ $activity->resource_id }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    @if($activity->details)
                                        <button onclick="toggleDetails(this)" class="text-blue-600 hover:text-blue-800 font-medium">
                                            View Details
                                        </button>
                                        <div class="details-content hidden mt-2 p-3 bg-gray-50 rounded text-xs text-gray-600 font-mono overflow-auto max-h-48">
                                            {{ json_encode($activity->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                                        </div>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-600">{{ $activity->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->created_at->format('H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->ip_address ?? 'Unknown' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-sm">
                                No activities found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($activities?->hasPages())
        <div class="mt-6">
            {{ $activities->links() }}
        </div>
    @endif
</x-container>

<script>
function toggleDetails(button) {
    const details = button.nextElementSibling;
    details.classList.toggle('hidden');
}
</script>
@endsection
