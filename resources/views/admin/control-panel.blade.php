@extends('layouts.app')

@section('page_title', 'Admin Control Panel')

@section('content')
<x-container>
    <!-- Page Header -->
    <x-page-header 
        title="Admin Control Panel" 
        subtitle="System Overview & Quick Actions"
    />

    <!-- System Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
                <div class="text-blue-500 text-3xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Across all roles and departments</p>
        </div>

        <!-- Total Departments -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Departments</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_departments'] ?? 0 }}</p>
                </div>
                <div class="text-green-500 text-3xl">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Active departments</p>
        </div>

        <!-- Active Requests -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Requests</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['active_requests'] ?? 0 }}</p>
                </div>
                <div class="text-yellow-500 text-3xl">
                    <i class="fas fa-clipboard"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Pending approval</p>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['pending_orders'] ?? 0 }}</p>
                </div>
                <div class="text-red-500 text-3xl">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Awaiting delivery</p>
        </div>
    </div>

    <!-- Quick Actions & System Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <a href="{{ route('users.create') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition">
                    <i class="fas fa-user-plus text-blue-500 text-2xl mb-2"></i>
                    <span class="text-sm text-gray-700 font-medium text-center">Add User</span>
                </a>

                <a href="{{ route('users.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 transition">
                    <i class="fas fa-users text-green-500 text-2xl mb-2"></i>
                    <span class="text-sm text-gray-700 font-medium text-center">Manage Users</span>
                </a>

                <a href="{{ route('admin.settings') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 transition">
                    <i class="fas fa-cog text-purple-500 text-2xl mb-2"></i>
                    <span class="text-sm text-gray-700 font-medium text-center">Settings</span>
                </a>

                <a href="{{ route('admin.vendors.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-orange-50 transition">
                    <i class="fas fa-store text-orange-500 text-2xl mb-2"></i>
                    <span class="text-sm text-gray-700 font-medium text-center">Vendors</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-indigo-50 transition">
                    <i class="fas fa-cube text-indigo-500 text-2xl mb-2"></i>
                    <span class="text-sm text-gray-700 font-medium text-center">Products</span>
                </a>

                <a href="{{ route('admin.activity-logs') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-history text-gray-500 text-2xl mb-2"></i>
                    <span class="text-sm text-gray-700 font-medium text-center">Activity Logs</span>
                </a>
            </div>
        </div>

        <!-- System Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">Laravel Version</p>
                    <p class="text-gray-900 font-medium">12.0</p>
                </div>
                <div>
                    <p class="text-gray-500">Database</p>
                    <p class="text-gray-900 font-medium">PostgreSQL 13</p>
                </div>
                <div>
                    <p class="text-gray-500">PHP Version</p>
                    <p class="text-gray-900 font-medium">8.2+</p>
                </div>
                <div>
                    <p class="text-gray-500">Server</p>
                    <p class="text-gray-900 font-medium">{{ gethostname() }}</p>
                </div>
                <div class="pt-3 border-t">
                    <p class="text-gray-500 text-xs">Last Updated</p>
                    <p class="text-gray-900 font-medium">{{ now()->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Statistics -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Distribution by Role</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <i class="fas fa-chalkboard-user text-blue-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Teachers</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['teachers'] ?? 0 }}</p>
            </div>

            <div class="text-center p-4 bg-green-50 rounded-lg">
                <i class="fas fa-user-tie text-green-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">HODs</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['hods'] ?? 0 }}</p>
            </div>

            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <i class="fas fa-crown text-purple-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Principals</p>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['principals'] ?? 0 }}</p>
            </div>

            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <i class="fas fa-landmark text-orange-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Trust Head</p>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['trust_heads'] ?? 0 }}</p>
            </div>

            <div class="text-center p-4 bg-red-50 rounded-lg">
                <i class="fas fa-user-shield text-red-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Admins</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['admins'] ?? 0 }}</p>
            </div>

            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <i class="fas fa-truck text-gray-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Providers</p>
                <p class="text-2xl font-bold text-gray-600">{{ $stats['providers'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($activities ?? [] as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ $activity->user->name ?? 'System' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge 
                                    :status="$activity->action" 
                                    :colors="[
                                        'created' => 'bg-green-100 text-green-800',
                                        'updated' => 'bg-blue-100 text-blue-800',
                                        'deleted' => 'bg-red-100 text-red-800',
                                        'approved' => 'bg-purple-100 text-purple-800',
                                    ]"
                                />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $activity->resource_type }}: {{ $activity->resource_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-sm">
                                No activity yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.activity-logs') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View All Activity →
            </a>
        </div>
    </div>
</x-container>
@endsection
