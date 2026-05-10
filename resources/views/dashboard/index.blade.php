@extends('layouts.app')

@section('content')
<x-container>
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Dashboard</h1>

    {{-- Teacher Dashboard --}}
    @if (auth()->user()->isTeacher())
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pending Requests</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingRequests }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">In Approval</p>
                <p class="text-3xl font-bold text-blue-600">{{ $approvedRequests }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Supplied</p>
                <p class="text-3xl font-bold text-green-600">{{ $suppliedRequests }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Rejected</p>
                <p class="text-3xl font-bold text-red-600">{{ $rejectedRequests }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Requests</h2>
            <div class="space-y-3">
                @foreach ($recentRequests as $request)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium text-gray-900">{{ $request->title }}</p>
                            <p class="text-sm text-gray-600">{{ $request->department->name }}</p>
                        </div>
                        <x-status-badge status="{{ $request->status }}" />
                    </div>
                @endforeach
            </div>
        </div>

    {{-- HOD Dashboard --}}
    @elseif (auth()->user()->isHOD())
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Department Requests</p>
                <p class="text-3xl font-bold text-blue-600">{{ $departmentRequests }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pending Approvals</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingApprovals }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Approved by Me</p>
                <p class="text-3xl font-bold text-green-600">{{ $approvedByMe }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Teachers</p>
                <p class="text-3xl font-bold text-purple-600">{{ $departmentTeachers }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Pending Approvals</h2>
            <div class="space-y-3">
                @foreach ($recentApprovals as $request)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium text-gray-900">{{ $request->title }}</p>
                            <p class="text-sm text-gray-600">{{ $request->requestedBy->name }}</p>
                        </div>
                        <a href="{{ route('stationary-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800">Review</a>
                    </div>
                @endforeach
            </div>
        </div>

    {{-- Principal Dashboard --}}
    @elseif (auth()->user()->isPrincipal())
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pending Approvals</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingApprovals }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Approved by Me</p>
                <p class="text-3xl font-bold text-green-600">{{ $approvedByMe }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Requests</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalRequests }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Departments</p>
                <p class="text-3xl font-bold text-purple-600">{{ $departmentsCount }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent HOD Approvals</h2>
            <div class="space-y-3">
                @foreach ($recentApprovals as $request)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium text-gray-900">{{ $request->title }}</p>
                            <p class="text-sm text-gray-600">{{ $request->department->name }} • {{ $request->requestedBy->name }}</p>
                        </div>
                        <a href="{{ route('stationary-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800">Review</a>
                    </div>
                @endforeach
            </div>
        </div>

    {{-- TrustHead Dashboard --}}
    @elseif (auth()->user()->isTrustHead())
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pending Approvals</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingApprovals }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Approved by Me</p>
                <p class="text-3xl font-bold text-green-600">{{ $approvedByMe }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Sent to Provider</p>
                <p class="text-3xl font-bold text-orange-600">{{ $sentToProvider }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Requests</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalRequests }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Principal Approvals</h2>
            <div class="space-y-3">
                @foreach ($recentApprovals as $request)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium text-gray-900">{{ $request->title }}</p>
                            <p class="text-sm text-gray-600">{{ $request->department->name }}</p>
                        </div>
                        <a href="{{ route('stationary-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800">Review</a>
                    </div>
                @endforeach
            </div>
        </div>

    {{-- Admin Dashboard --}}
    @elseif (auth()->user()->isAdmin())
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Requests</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalRequests }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Orders</p>
                <p class="text-3xl font-bold text-green-600">{{ $totalOrders }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-purple-600">{{ $totalUsers }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Departments</p>
                <p class="text-3xl font-bold text-orange-600">{{ $totalDepartments }}</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $pendingRequests }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">In Approval</p>
                <p class="text-2xl font-bold text-blue-600">{{ $inApprovalRequests }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Supplied</p>
                <p class="text-2xl font-bold text-green-600">{{ $suppliedRequests }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Requests</h2>
                <div class="space-y-3">
                    @foreach ($recentRequests as $request)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium text-gray-900">{{ $request->title }}</p>
                                <p class="text-sm text-gray-600">{{ $request->department->name }}</p>
                            </div>
                            <x-status-badge status="{{ $request->status }}" />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Orders</h2>
                <div class="space-y-3">
                    @foreach ($recentOrders as $order)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->stationaryRequest->title }}</p>
                                <p class="text-sm text-gray-600">{{ $order->vendor->name }}</p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium @if($order->status == 'Pending') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                {{ $order->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    {{-- Provider Dashboard --}}
    @elseif (auth()->user()->isProvider())
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Assigned Orders</p>
                <p class="text-3xl font-bold text-blue-600">{{ $assignedOrders }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pending Delivery</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingDelivery }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Confirmed</p>
                <p class="text-3xl font-bold text-orange-600">{{ $confirmedOrders }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Delivered</p>
                <p class="text-3xl font-bold text-green-600">{{ $deliveredOrders }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Orders</h2>
            <div class="space-y-3">
                @foreach ($recentOrders as $order)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium text-gray-900">{{ $order->stationaryRequest->title }}</p>
                            <p class="text-sm text-gray-600">{{ $order->stationaryRequest->department->name }} • {{ $order->items->count() }} items</p>
                        </div>
                        <a href="{{ route('orders.track', $order) }}" class="text-blue-600 hover:text-blue-800">Track</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-container>
@endsection
