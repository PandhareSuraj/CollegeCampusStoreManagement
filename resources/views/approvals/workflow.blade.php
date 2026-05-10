@extends('layouts.app')

@section('page_title', 'Approval Workflow')

@section('content')
<x-container>
    <x-page-header 
        title="Approval Workflow"
        subtitle="System approval process visualization"
    />

    <!-- Workflow Steps -->
    <div class="bg-white rounded-lg shadow p-8 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-8">5-Level Approval Process</h2>
        
        <div class="flex items-center justify-between mb-12">
            <!-- Step 1: Pending -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-16 h-16 rounded-full bg-yellow-100 text-yellow-800 flex items-center justify-center mb-3">
                    <span class="text-2xl">📝</span>
                </div>
                <h3 class="font-semibold text-gray-900 text-center">Level 1: Pending</h3>
                <p class="text-xs text-gray-600 text-center mt-1">Request Created</p>
            </div>
            <div class="flex-1 border-t-2 border-gray-300 mx-2"></div>

            <!-- Step 2: HOD Approval -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center mb-3">
                    <span class="text-2xl">👤</span>
                </div>
                <h3 class="font-semibold text-gray-900 text-center">Level 2: HOD</h3>
                <p class="text-xs text-gray-600 text-center mt-1">Department Approval</p>
            </div>
            <div class="flex-1 border-t-2 border-gray-300 mx-2"></div>

            <!-- Step 3: Principal Approval -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center mb-3">
                    <span class="text-2xl">🎓</span>
                </div>
                <h3 class="font-semibold text-gray-900 text-center">Level 3: Principal</h3>
                <p class="text-xs text-gray-600 text-center mt-1">Institution Approval</p>
            </div>
            <div class="flex-1 border-t-2 border-gray-300 mx-2"></div>

            <!-- Step 4: TrustHead Approval -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-16 h-16 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center mb-3">
                    <span class="text-2xl">👑</span>
                </div>
                <h3 class="font-semibold text-gray-900 text-center">Level 4: TrustHead</h3>
                <p class="text-xs text-gray-600 text-center mt-1">Trust Approval</p>
            </div>
            <div class="flex-1 border-t-2 border-gray-300 mx-2"></div>

            <!-- Step 5: Sent to Provider -->
            <div class="flex flex-col items-center flex-1">
                <div class="w-16 h-16 rounded-full bg-orange-100 text-orange-800 flex items-center justify-center mb-3">
                    <span class="text-2xl">📦</span>
                </div>
                <h3 class="font-semibold text-gray-900 text-center">Level 5: Provider</h3>
                <p class="text-xs text-gray-600 text-center mt-1">Order Creation</p>
            </div>
        </div>

        <!-- Final: Supplied -->
        <div class="flex items-center justify-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-green-100 text-green-800 flex items-center justify-center mb-3">
                    <span class="text-2xl">✓</span>
                </div>
                <h3 class="font-semibold text-gray-900 text-center">Complete: Supplied</h3>
                <p class="text-xs text-gray-600 text-center mt-1">Items Delivered</p>
            </div>
        </div>
    </div>

    <!-- Workflow Details -->
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-yellow-900 mb-4">📝 Level 1: Pending</h3>
            <div class="space-y-2 text-sm text-yellow-800">
                <p><strong>Actors:</strong> Teacher</p>
                <p><strong>Action:</strong> Create stationary request</p>
                <p><strong>Required:</strong> Title, description, items</p>
                <p><strong>Next:</strong> Sent to HOD for approval</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">👤 Level 2: HOD Approval</h3>
            <div class="space-y-2 text-sm text-blue-800">
                <p><strong>Actors:</strong> Head of Department</p>
                <p><strong>Action:</strong> Review & approve/reject</p>
                <p><strong>Authority:</strong> Department level</p>
                <p><strong>Next:</strong> Sent to Principal if approved</p>
            </div>
        </div>

        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-indigo-900 mb-4">🎓 Level 3: Principal</h3>
            <div class="space-y-2 text-sm text-indigo-800">
                <p><strong>Actors:</strong> Principal</p>
                <p><strong>Action:</strong> Review & approve/reject</p>
                <p><strong>Authority:</strong> Institution level</p>
                <p><strong>Next:</strong> Sent to TrustHead if approved</p>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-purple-900 mb-4">👑 Level 4: TrustHead</h3>
            <div class="space-y-2 text-sm text-purple-800">
                <p><strong>Actors:</strong> Trust Head</p>
                <p><strong>Action:</strong> Review & approve/reject</p>
                <p><strong>Authority:</strong> Organization level</p>
                <p><strong>Next:</strong> Sent to Admin/Provider if approved</p>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-orange-900 mb-4">📦 Level 5: Provider Order</h3>
            <div class="space-y-2 text-sm text-orange-800">
                <p><strong>Actors:</strong> Admin, Provider</p>
                <p><strong>Action:</strong> Create order with vendor</p>
                <p><strong>Authority:</strong> Procurement level</p>
                <p><strong>Next:</strong> Provider marks as supplied</p>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-900 mb-4">✓ Final: Supplied</h3>
            <div class="space-y-2 text-sm text-green-800">
                <p><strong>Actors:</strong> Provider</p>
                <p><strong>Action:</strong> Mark items as received</p>
                <p><strong>Status:</strong> Request complete</p>
                <p><strong>Next:</strong> Archive & close</p>
            </div>
        </div>
    </div>

    <!-- Rejection Flow -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mt-6">
        <h2 class="text-lg font-semibold text-red-900 mb-4">⚠️ Rejection Flow</h2>
        <p class="text-sm text-red-800 mb-3">
            At any level (2-4), an approver can reject the request:
        </p>
        <ul class="text-sm text-red-800 space-y-1">
            <li>✗ Request marked as "Rejected"</li>
            <li>✗ Requester notified with reason</li>
            <li>✗ Request workflow stops</li>
            <li>✗ Requester can modify and resubmit</li>
        </ul>
    </div>

    <!-- Key Statistics -->
    <div class="grid grid-cols-4 gap-4 mt-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Avg. Time per Level</p>
            <p class="text-2xl font-bold text-gray-900">2-3 days</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Total Workflow Time</p>
            <p class="text-2xl font-bold text-gray-900">10-15 days</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Typical Approval Rate</p>
            <p class="text-2xl font-bold text-gray-900">85%</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Rejection Rate</p>
            <p class="text-2xl font-bold text-gray-900">15%</p>
        </div>
    </div>

    <!-- Links -->
    <div class="mt-6 flex gap-4">
        <a 
            href="{{ route('approvals.pending') }}"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
        >
            View Pending Approvals
        </a>
        <a 
            href="{{ route('dashboard') }}"
            class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium"
        >
            Back to Dashboard
        </a>
    </div>
</x-container>
@endsection
