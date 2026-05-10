@extends('layouts.app')

@section('page_title', 'System Settings')

@section('content')
<x-container>
    <!-- Page Header -->
    <x-page-header 
        title="System Settings" 
        subtitle="Configure application parameters"
    />

    @if ($errors->any())
        <x-alerts type="error" :messages="$errors->all()" class="mb-6" />
    @endif

    @if (session('success'))
        <x-alerts type="success" :messages="[session('success')]" class="mb-6" />
    @endif

    <!-- Settings Tabs Navigation -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow border-b border-gray-200">
            <div class="flex overflow-x-auto">
                <button onclick="showTab('general')" id="tab-general" class="px-6 py-3 font-medium text-blue-600 border-b-2 border-blue-600 whitespace-nowrap">
                    General
                </button>
                <button onclick="showTab('email')" id="tab-email" class="px-6 py-3 font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700">
                    Email
                </button>
                <button onclick="showTab('approval')" id="tab-approval" class="px-6 py-3 font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700">
                    Approvals
                </button>
                <button onclick="showTab('order')" id="tab-order" class="px-6 py-3 font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700">
                    Orders
                </button>
            </div>
        </div>
    </div>

    <!-- General Settings -->
    <div id="general" class="tab-content">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Application Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Application Name
                    </label>
                    <input type="text" name="app_name" value="{{ env('APP_NAME', 'Campus Store') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Support Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Support Email
                    </label>
                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'support@campus.edu' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Support Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Support Phone
                    </label>
                    <input type="tel" name="support_phone" value="{{ $settings['support_phone'] ?? '+91 0000000000' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Business Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Business Address
                    </label>
                    <input type="text" name="business_address" value="{{ $settings['business_address'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Application Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Application Description
                </label>
                <textarea name="app_description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $settings['app_description'] ?? '' }}</textarea>
            </div>

            <div class="flex justify-end">
                <x-button type="submit" color="blue">Save General Settings</x-button>
            </div>
        </form>
    </div>

    <!-- Email Settings -->
    <div id="email" class="tab-content hidden">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Mail From Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mail From Address
                    </label>
                    <input type="email" name="mail_from_address" value="{{ env('MAIL_FROM_ADDRESS', 'noreply@campus.edu') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Mail From Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mail From Name
                    </label>
                    <input type="text" name="mail_from_name" value="{{ env('MAIL_FROM_NAME', 'Campus Store') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Mail Driver -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mail Driver
                    </label>
                    <select name="mail_driver" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="smtp">SMTP</option>
                        <option value="mailgun">Mailgun</option>
                        <option value="sendgrid">SendGrid</option>
                        <option value="log">Log</option>
                    </select>
                </div>

                <!-- Mail Host -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mail Host
                    </label>
                    <input type="text" name="mail_host" value="{{ env('MAIL_HOST', 'smtp.mailtrap.io') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Notification Email -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <input type="checkbox" name="send_notifications" {{ $settings['send_notifications'] ?? false ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="ml-2">Send Email Notifications for Approvals</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">Enable to notify approvers about pending requests</p>
            </div>

            <div class="flex justify-end">
                <x-button type="submit" color="blue">Save Email Settings</x-button>
            </div>
        </form>
    </div>

    <!-- Approval Settings -->
    <div id="approval" class="tab-content hidden">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Auto-Approve Threshold -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Auto-Approve Amount Threshold (₹)
                    </label>
                    <input type="number" name="auto_approve_threshold" value="{{ $settings['auto_approve_threshold'] ?? 5000 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Requests below this amount auto-approve at HOD level</p>
                </div>

                <!-- Approval Timeout -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Approval Timeout (days)
                    </label>
                    <input type="number" name="approval_timeout_days" value="{{ $settings['approval_timeout_days'] ?? 5 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Days before approval request escalates to next level</p>
                </div>

                <!-- Require HOD Approval -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <input type="checkbox" name="require_hod_approval" {{ $settings['require_hod_approval'] ?? true ? 'checked' : '' }} class="rounded border-gray-300">
                        <span class="ml-2">Require HOD Approval</span>
                    </label>
                </div>

                <!-- Require Principal Approval -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <input type="checkbox" name="require_principal_approval" {{ $settings['require_principal_approval'] ?? true ? 'checked' : '' }} class="rounded border-gray-300">
                        <span class="ml-2">Require Principal Approval</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <x-button type="submit" color="blue">Save Approval Settings</x-button>
            </div>
        </form>
    </div>

    <!-- Order Settings -->
    <div id="order" class="tab-content hidden">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Default Delivery Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Default Delivery Days
                    </label>
                    <input type="number" name="default_delivery_days" value="{{ $settings['default_delivery_days'] ?? 7 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Expected delivery window for orders</p>
                </div>

                <!-- Enable Order Tracking -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <input type="checkbox" name="enable_order_tracking" {{ $settings['enable_order_tracking'] ?? true ? 'checked' : '' }} class="rounded border-gray-300">
                        <span class="ml-2">Enable Order Tracking</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Allow users to track order status</p>
                </div>

                <!-- Require Delivery Confirmation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <input type="checkbox" name="require_delivery_confirmation" {{ $settings['require_delivery_confirmation'] ?? true ? 'checked' : '' }} class="rounded border-gray-300">
                        <span class="ml-2">Require Delivery Confirmation</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Provider must confirm items received</p>
                </div>

                <!-- Maximum Items Per Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Maximum Items Per Order
                    </label>
                    <input type="number" name="max_items_per_order" value="{{ $settings['max_items_per_order'] ?? 50 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <x-button type="submit" color="blue">Save Order Settings</x-button>
            </div>
        </form>
    </div>
</x-container>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active styling from all tabs
    const tabs = document.querySelectorAll('[id^="tab-"]');
    tabs.forEach(tab => {
        tab.classList.remove('text-blue-600', 'border-blue-600');
        tab.classList.add('text-gray-500', 'border-transparent');
    });

    // Show selected tab content
    document.getElementById(tabName).classList.remove('hidden');

    // Add active styling to clicked tab
    document.getElementById('tab-' + tabName).classList.remove('text-gray-500', 'border-transparent');
    document.getElementById('tab-' + tabName).classList.add('text-blue-600', 'border-blue-600');
}
</script>
@endsection
