<?php

namespace App\Http\Controllers;

use App\Models\StationaryRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Department;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Show admin control panel.
     */
    public function controlPanel(): View
    {
        $this->authorize('isAdmin');

        $stats = [
            'total_users' => User::count(),
            'total_departments' => Department::count(),
            'total_vendors' => Vendor::count(),
            'total_products' => Product::count(),
            'total_requests' => StationaryRequest::count(),
            'total_orders' => Order::count(),
        ];

        $recentUsers = User::latest('created_at')->take(5)->get();
        $recentRequests = StationaryRequest::latest('created_at')->take(5)->get();

        return view('admin.control-panel', compact('stats', 'recentUsers', 'recentRequests'));
    }

    /**
     * Display system activity logs.
     */
    public function activityLogs(): View
    {
        $this->authorize('isAdmin');

        $logs = StationaryRequest::with('requestedBy')
            ->latest('updated_at')
            ->paginate(30);

        return view('admin.activity-logs', compact('logs'));
    }

    /**
     * Display system settings.
     */
    public function settings(): View
    {
        $this->authorize('isAdmin');

        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'max_request_items' => ['integer', 'min:1', 'max:100'],
            'approval_timeout_days' => ['integer', 'min:1', 'max:30'],
            'email_notifications' => ['boolean'],
            'require_quotation' => ['boolean'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Display vendor management.
     */
    public function vendors(): View
    {
        $this->authorize('isAdmin');

        $vendors = Vendor::paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Show create vendor form.
     */
    public function createVendor(): View
    {
        $this->authorize('isAdmin');

        return view('admin.vendors.create');
    }

    /**
     * Store new vendor.
     */
    public function storeVendor(Request $request): RedirectResponse
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:vendors,email'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'active' => ['boolean'],
        ]);

        Vendor::create($validated);

        return redirect()->route('admin.vendors')
            ->with('success', 'Vendor created successfully.');
    }

    /**
     * Show edit vendor form.
     */
    public function editVendor(Vendor $vendor): View
    {
        $this->authorize('isAdmin');

        return view('admin.vendors.edit', compact('vendor'));
    }

    /**
     * Update vendor.
     */
    public function updateVendor(Request $request, Vendor $vendor): RedirectResponse
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:vendors,email,' . $vendor->id],
            'phone' => ['sometimes', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'active' => ['boolean'],
        ]);

        $vendor->update($validated);

        return redirect()->route('admin.vendors')
            ->with('success', 'Vendor updated successfully.');
    }

    /**
     * Delete vendor.
     */
    public function deleteVendor(Vendor $vendor): RedirectResponse
    {
        $this->authorize('isAdmin');

        $vendor->delete();

        return redirect()->route('admin.vendors')
            ->with('success', 'Vendor deleted successfully.');
    }

    /**
     * Display product management.
     */
    public function products(): View
    {
        $this->authorize('isAdmin');

        $products = Product::paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show create product form.
     */
    public function createProduct(): View
    {
        $this->authorize('isAdmin');

        return view('admin.products.create');
    }

    /**
     * Store new product.
     */
    public function storeProduct(Request $request): RedirectResponse
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'unique:products,sku', 'max:50'],
            'category' => ['required', 'string', 'max:100'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'active' => ['boolean'],
        ]);

        Product::create($validated);

        return redirect()->route('admin.products')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show edit product form.
     */
    public function editProduct(Product $product): View
    {
        $this->authorize('isAdmin');

        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product.
     */
    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'sku' => ['sometimes', 'string', 'unique:products,sku,' . $product->id, 'max:50'],
            'category' => ['sometimes', 'string', 'max:100'],
            'unit_price' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'active' => ['boolean'],
        ]);

        $product->update($validated);

        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function deleteProduct(Product $product): RedirectResponse
    {
        $this->authorize('isAdmin');

        $product->delete();

        return redirect()->route('admin.products')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Display reports.
     */
    public function reports(): View
    {
        $this->authorize('isAdmin');

        $requestsByStatus = StationaryRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        $ordersByStatus = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        $requestsByDepartment = Department::withCount('stationaryRequests')
            ->get();

        $topVendors = Vendor::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports', compact(
            'requestsByStatus',
            'ordersByStatus',
            'requestsByDepartment',
            'topVendors'
        ));
    }

    /**
     * Check authorization for admin operations.
     */
    protected function authorize($action): void
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
