<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Requests\UpdateDeliveryStatusRequest;
use App\Http\Requests\ReceiveItemsRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StationaryRequest;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Order::class);

        $user = auth()->user();
        
        $query = Order::with([
            'stationaryRequest.requestedBy',
            'vendor',
            'items.product'
        ])->orderBy('created_at', 'desc');

        // Filter based on user role
        if ($user->isProvider()) {
            $query->where('vendor_id', $user->vendor_id ?? 0);
        } elseif ($user->isHOD()) {
            // HODs see orders from their department's requests
            $query->whereHas('stationaryRequest', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        }
        // Admin sees all orders

        $orders = $query->paginate(15);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(): View
    {
        $this->authorize('create', Order::class);

        $stationaryRequests = StationaryRequest::where('status', 'Trust_Approved')
            ->doesntHave('orders')
            ->get();
        
        $vendors = Vendor::where('active', true)->get();

        return view('orders.create', compact('stationaryRequests', 'vendors'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $stationaryRequest = StationaryRequest::findOrFail($validated['stationary_request_id']);
        
        $this->authorize('create', Order::class);

        // Create order
        $order = Order::create([
            'stationary_request_id' => $validated['stationary_request_id'],
            'vendor_id' => $validated['vendor_id'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'Pending',
        ]);

        // Copy request items to order items
        foreach ($stationaryRequest->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->unit_price ?? 0,
            ]);
        }

        // Update stationary request status
        $stationaryRequest->update(['status' => 'Sent_to_Provider']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load([
            'stationaryRequest.requestedBy',
            'stationaryRequest.department',
            'vendor',
            'items.product',
        ]);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order): View
    {
        $this->authorize('update', $order);

        $vendors = Vendor::where('active', true)->get();

        return view('orders.edit', compact('order', 'vendors'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        if (isset($validated['expected_delivery_date'])) {
            $order->expected_delivery_date = $validated['expected_delivery_date'];
        }
        if (isset($validated['notes'])) {
            $order->notes = $validated['notes'];
        }

        $order->save();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Delete the specified order.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $this->authorize('delete', $order);

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    /**
     * Confirm order (mark as confirmed).
     */
    public function confirm(Order $order): RedirectResponse
    {
        $this->authorize('confirm', $order);

        $order->update(['status' => 'Confirmed']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order confirmed successfully.');
    }

    /**
     * Update delivery status (provider-only).
     */
    public function updateDeliveryStatus(UpdateDeliveryStatusRequest $request, Order $order): RedirectResponse
    {
        $this->authorize('updateDeliveryStatus', $order);

        $validated = $request->validated();

        $order->update([
            'delivery_status' => $validated['delivery_status'],
            'estimated_arrival_date' => $validated['estimated_arrival_date'] ?? null,
            'delivery_notes' => $validated['delivery_notes'] ?? null,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Delivery status updated successfully.');
    }

    /**
     * Show form for receiving items.
     */
    public function receiveForm(Order $order): View
    {
        $this->authorize('receiveItems', $order);

        $order->load('items.product');

        return view('orders.receive-items', compact('order'));
    }

    /**
     * Record received items.
     */
    public function receiveItems(ReceiveItemsRequest $request, Order $order): RedirectResponse
    {
        $this->authorize('receiveItems', $order);

        $validated = $request->validated();

        $order->update([
            'received_date' => $validated['received_date'],
            'received_by' => $validated['received_by'],
            'receipt_notes' => $validated['receipt_notes'] ?? null,
            'status' => 'Delivered',
        ]);

        // Update order items with received quantities
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                OrderItem::where('order_id', $order->id)
                    ->where('product_id', $item['product_id'])
                    ->update([
                        'received_quantity' => $item['received_quantity'],
                        'condition_notes' => $item['condition_notes'] ?? null,
                    ]);
            }
        }

        // Update stationary request status
        $order->stationaryRequest->update(['status' => 'Supplied']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Items received successfully.');
    }

    /**
     * Show delivery tracking view.
     */
    public function trackDelivery(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load([
            'vendor',
            'items.product',
            'stationaryRequest.requestedBy',
        ]);

        return view('orders.track', compact('order'));
    }
}
