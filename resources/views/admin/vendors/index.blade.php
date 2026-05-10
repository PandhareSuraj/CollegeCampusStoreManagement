@extends('layouts.app')

@section('page_title', 'Manage Vendors')

@section('content')
<x-container>
    <!-- Page Header -->
    <x-page-header 
        title="Vendors" 
        subtitle="Manage system vendors and suppliers"
    >
        @can('create', App\Models\Vendor::class)
            <a href="{{ route('admin.vendors.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Add Vendor
            </a>
        @endcan
    </x-page-header>

    @if (session('success'))
        <x-alerts type="success" :messages="[session('success')]" class="mb-6" />
    @endif

    <!-- Filter Bar -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.vendors.index') }}" class="flex gap-4 flex-col md:flex-row md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or contact..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <a href="{{ route('admin.vendors.index') }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Vendors Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($vendors ?? [] as $vendor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $vendor->name }}</p>
                                <p class="text-xs text-gray-500">ID: {{ $vendor->id }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-700">{{ $vendor->contact_person }}</p>
                                <p class="text-xs text-gray-500">{{ $vendor->phone }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $vendor->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $vendor->category ?? 'General' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge 
                                    :status="$vendor->is_active ? 'active' : 'inactive'"
                                    :colors="[
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-gray-100 text-gray-800',
                                    ]"
                                />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $vendor->orders_count ?? 0 }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                @can('update', $vendor)
                                    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endcan

                                @can('delete', $vendor)
                                    <form method="POST" action="{{ route('admin.vendors.destroy', $vendor) }}" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No vendors found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($vendors?->hasPages())
        <div class="mt-6">
            {{ $vendors->links() }}
        </div>
    @endif
</x-container>
@endsection
