<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStationaryRequestRequest;
use App\Http\Requests\UpdateStationaryRequestRequest;
use App\Http\Requests\ApproveStationaryRequestRequest;
use App\Http\Requests\RejectStationaryRequestRequest;
use App\Http\Requests\SendToProviderRequest;
use App\Models\StationaryRequest;
use App\Models\RequestItem;
use App\Models\Approval;
use App\Models\Department;
use App\Models\Product;
use App\Enums\RequestStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StationaryRequestController extends Controller
{
    /**
     * Display a listing of stationary requests.
     */
    public function index(): View
    {
        $this->authorize('viewAny', StationaryRequest::class);

        $user = auth()->user();
        
        $query = StationaryRequest::with(['requestedBy', 'department', 'approvals'])
            ->orderBy('created_at', 'desc');

        // Filter based on user role
        if ($user->isTeacher()) {
            $query->where('requested_by', $user->id);
        } elseif ($user->isHOD()) {
            $query->where('department_id', $user->department_id);
        }
        // Admin and other roles see all requests

        $requests = $query->paginate(15);

        return view('stationary-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new stationary request.
     */
    public function create(): View
    {
        $this->authorize('create', StationaryRequest::class);

        $user = auth()->user();
        $departments = $user->isTeacher() || $user->isHOD()
            ? Department::where('id', $user->department_id)->get()
            : Department::all();

        $products = Product::where('active', true)->get();

        return view('stationary-requests.create', compact('departments', 'products'));
    }

    /**
     * Store a newly created stationary request in storage.
     */
    public function store(StoreStationaryRequestRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Create the stationary request
        $stationaryRequest = StationaryRequest::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'department_id' => $validated['department_id'],
            'requested_by' => auth()->id(),
            'status' => RequestStatus::PENDING->value,
        ]);

        // Create request items
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                RequestItem::create([
                    'stationary_request_id' => $stationaryRequest->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }
        }

        return redirect()->route('stationary-requests.show', $stationaryRequest)
            ->with('success', 'Stationary request created successfully.');
    }

    /**
     * Display the specified stationary request.
     */
    public function show(StationaryRequest $stationaryRequest): View
    {
        $this->authorize('view', $stationaryRequest);

        $stationaryRequest->load([
            'requestedBy',
            'department',
            'items.product',
            'approvals.approvedBy',
        ]);

        return view('stationary-requests.show', compact('stationaryRequest'));
    }

    /**
     * Show the form for editing the specified stationary request.
     */
    public function edit(StationaryRequest $stationaryRequest): View
    {
        $this->authorize('update', $stationaryRequest);

        $stationaryRequest->load('items');
        
        $departments = Department::all();
        $products = Product::where('active', true)->get();

        return view('stationary-requests.edit', compact('stationaryRequest', 'departments', 'products'));
    }

    /**
     * Update the specified stationary request in storage.
     */
    public function update(UpdateStationaryRequestRequest $request, StationaryRequest $stationaryRequest): RedirectResponse
    {
        $validated = $request->validated();

        // Update basic fields if provided
        if (isset($validated['title'])) {
            $stationaryRequest->title = $validated['title'];
        }
        if (isset($validated['description'])) {
            $stationaryRequest->description = $validated['description'];
        }

        $stationaryRequest->save();

        // Update items if provided
        if (!empty($validated['items'])) {
            // Delete old items and create new ones
            $stationaryRequest->items()->delete();

            foreach ($validated['items'] as $item) {
                RequestItem::create([
                    'stationary_request_id' => $stationaryRequest->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }
        }

        return redirect()->route('stationary-requests.show', $stationaryRequest)
            ->with('success', 'Stationary request updated successfully.');
    }

    /**
     * Delete the specified stationary request.
     */
    public function destroy(StationaryRequest $stationaryRequest): RedirectResponse
    {
        $this->authorize('delete', $stationaryRequest);

        $stationaryRequest->delete();

        return redirect()->route('stationary-requests.index')
            ->with('success', 'Stationary request deleted successfully.');
    }

    /**
     * Approve the specified stationary request.
     */
    public function approve(ApproveStationaryRequestRequest $request, StationaryRequest $stationaryRequest): RedirectResponse
    {
        $this->authorize('approve', $stationaryRequest);

        $validated = $request->validated();
        $user = auth()->user();

        // Determine the next status based on approval level
        $nextStatus = match (true) {
            $user->isHOD() => RequestStatus::HOD_APPROVED,
            $user->isPrincipal() => RequestStatus::PRINCIPAL_APPROVED,
            $user->isTrustHead() => RequestStatus::TRUST_APPROVED,
            $user->isAdmin() => RequestStatus::TRUST_APPROVED,
        };

        // Create approval record
        Approval::create([
            'stationary_request_id' => $stationaryRequest->id,
            'approved_by' => $user->id,
            'approval_level' => $user->approvalLevel(),
            'status' => 'approved',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update request status
        $stationaryRequest->update(['status' => $nextStatus->value]);

        return redirect()->route('stationary-requests.show', $stationaryRequest)
            ->with('success', 'Request approved successfully.');
    }

    /**
     * Reject the specified stationary request.
     */
    public function reject(RejectStationaryRequestRequest $request, StationaryRequest $stationaryRequest): RedirectResponse
    {
        $this->authorize('reject', $stationaryRequest);

        $validated = $request->validated();
        $user = auth()->user();

        // Create rejection approval record
        Approval::create([
            'stationary_request_id' => $stationaryRequest->id,
            'approved_by' => $user->id,
            'approval_level' => $user->approvalLevel(),
            'status' => 'rejected',
            'notes' => $validated['rejection_reason'],
        ]);

        // Update request status
        $stationaryRequest->update(['status' => RequestStatus::REJECTED->value]);

        return redirect()->route('stationary-requests.show', $stationaryRequest)
            ->with('success', 'Request rejected successfully.');
    }

    /**
     * Send the approved request to provider (create order).
     */
    public function sendToProvider(SendToProviderRequest $request, StationaryRequest $stationaryRequest): RedirectResponse
    {
        $this->authorize('sendToProvider', $stationaryRequest);

        $validated = $request->validated();

        // Create order from the stationary request
        $order = $stationaryRequest->orders()->create([
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

        // Update request status
        $stationaryRequest->update(['status' => RequestStatus::SENT_TO_PROVIDER->value]);

        return redirect()->route('stationary-requests.show', $stationaryRequest)
            ->with('success', 'Request sent to provider. Order created successfully.');
    }

    /**
     * Mark request items as supplied/delivered.
     */
    public function markSupplied(StationaryRequest $stationaryRequest): RedirectResponse
    {
        $this->authorize('markSupplied', $stationaryRequest);

        $stationaryRequest->update(['status' => RequestStatus::SUPPLIED->value]);

        return redirect()->route('stationary-requests.show', $stationaryRequest)
            ->with('success', 'Request marked as supplied.');
    }

    /**
     * View approval history for a request.
     */
    public function viewApprovals(StationaryRequest $stationaryRequest): View
    {
        $this->authorize('viewApprovals', $stationaryRequest);

        $stationaryRequest->load('approvals.approvedBy');
        $approvals = $stationaryRequest->approvals;

        return view('stationary-requests.approvals', compact('stationaryRequest', 'approvals'));
    }

    /**
     * Add items to an existing request.
     */
    public function addItems(StationaryRequest $stationaryRequest): View
    {
        $this->authorize('addItems', $stationaryRequest);

        $products = Product::where('active', true)->get();

        return view('stationary-requests.add-items', compact('stationaryRequest', 'products'));
    }

    /**
     * Store newly added items.
     */
    public function storeItems(Request $request, StationaryRequest $stationaryRequest): RedirectResponse
    {
        $this->authorize('addItems', $stationaryRequest);

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'items.*.notes' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($validated['items'] as $item) {
            RequestItem::create([
                'stationary_request_id' => $stationaryRequest->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('stationary-requests.show', $stationaryRequest)
            ->with('success', 'Items added to request successfully.');
    }
}
