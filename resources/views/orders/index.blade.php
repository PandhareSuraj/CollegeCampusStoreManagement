@extends('layouts.app')

@section('content')
<x-container>
    <x-page-header title="Orders">
        <x-slot name="actions">
            @can('create', App\Models\Order::class)
                <x-button href="{{ route('orders.create') }}" label="+ New Order" variant="primary" />
            @endcan
        </x-slot>
    </x-page-header>

    <x-alerts />

    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Request</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Vendor</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Items</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Expected Delivery</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $order->stationaryRequest->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->vendor->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium @if($order->status == 'Pending') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->items->count() }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->expected_delivery_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-container>
@endsection
