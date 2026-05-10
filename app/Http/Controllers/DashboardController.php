<?php

namespace App\Http\Controllers;

use App\Models\StationaryRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Department;
use App\Enums\RequestStatus;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the general dashboard (dispatches to role-specific dashboard).
     */
    public function index(): View
    {
        $user = auth()->user();

        return match (true) {
            $user->isTeacher() => $this->teacherDashboard($user),
            $user->isHOD() => $this->hodDashboard($user),
            $user->isPrincipal() => $this->principalDashboard($user),
            $user->isTrustHead() => $this->trustHeadDashboard($user),
            $user->isAdmin() => $this->adminDashboard($user),
            $user->isProvider() => $this->providerDashboard($user),
            default => view('dashboard.index'),
        };
    }

    /**
     * Teacher dashboard - shows their requests.
     */
    private function teacherDashboard($user): View
    {
        $pendingRequests = StationaryRequest::where('requested_by', $user->id)
            ->where('status', RequestStatus::PENDING->value)
            ->count();

        $approvedRequests = StationaryRequest::where('requested_by', $user->id)
            ->whereIn('status', ['HOD_Approved', 'Principal_Approved', 'Trust_Approved', 'Sent_to_Provider'])
            ->count();

        $suppliedRequests = StationaryRequest::where('requested_by', $user->id)
            ->where('status', RequestStatus::SUPPLIED->value)
            ->count();

        $rejectedRequests = StationaryRequest::where('requested_by', $user->id)
            ->where('status', RequestStatus::REJECTED->value)
            ->count();

        $recentRequests = StationaryRequest::where('requested_by', $user->id)
            ->with('department')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('dashboard.teacher', compact(
            'pendingRequests',
            'approvedRequests',
            'suppliedRequests',
            'rejectedRequests',
            'recentRequests'
        ));
    }

    /**
     * HOD dashboard - shows department requests and approvals.
     */
    private function hodDashboard($user): View
    {
        $departmentRequests = StationaryRequest::where('department_id', $user->department_id)->count();

        $pendingApprovals = StationaryRequest::where('department_id', $user->department_id)
            ->where('status', RequestStatus::PENDING->value)
            ->count();

        $approvedByMe = StationaryRequest::where('department_id', $user->department_id)
            ->where('status', RequestStatus::HOD_APPROVED->value)
            ->count();

        $rejectedRequests = StationaryRequest::where('department_id', $user->department_id)
            ->where('status', RequestStatus::REJECTED->value)
            ->count();

        $recentApprovals = StationaryRequest::where('department_id', $user->department_id)
            ->with(['requestedBy', 'approvals.approvedBy'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $departmentTeachers = User::where('department_id', $user->department_id)
            ->where('role', 'teacher')
            ->count();

        return view('dashboard.hod', compact(
            'departmentRequests',
            'pendingApprovals',
            'approvedByMe',
            'rejectedRequests',
            'recentApprovals',
            'departmentTeachers'
        ));
    }

    /**
     * Principal dashboard - shows HOD-approved requests for approval.
     */
    private function principalDashboard($user): View
    {
        $pendingApprovals = StationaryRequest::where('status', RequestStatus::HOD_APPROVED->value)
            ->count();

        $approvedByMe = StationaryRequest::where('status', RequestStatus::PRINCIPAL_APPROVED->value)
            ->count();

        $totalRequests = StationaryRequest::count();

        $departmentsCount = Department::count();

        $recentApprovals = StationaryRequest::with(['requestedBy', 'department', 'approvals.approvedBy'])
            ->where('status', RequestStatus::HOD_APPROVED->value)
            ->latest('created_at')
            ->take(10)
            ->get();

        $approvedRequests = StationaryRequest::where('status', RequestStatus::PRINCIPAL_APPROVED->value)
            ->count();

        return view('dashboard.principal', compact(
            'pendingApprovals',
            'approvedByMe',
            'totalRequests',
            'departmentsCount',
            'recentApprovals',
            'approvedRequests'
        ));
    }

    /**
     * Trust Head dashboard - shows principal-approved requests for approval.
     */
    private function trustHeadDashboard($user): View
    {
        $pendingApprovals = StationaryRequest::where('status', RequestStatus::PRINCIPAL_APPROVED->value)
            ->count();

        $approvedByMe = StationaryRequest::where('status', RequestStatus::TRUST_APPROVED->value)
            ->count();

        $totalRequests = StationaryRequest::count();

        $sentToProvider = StationaryRequest::where('status', RequestStatus::SENT_TO_PROVIDER->value)
            ->count();

        $recentApprovals = StationaryRequest::with(['requestedBy', 'department', 'approvals.approvedBy'])
            ->where('status', RequestStatus::PRINCIPAL_APPROVED->value)
            ->latest('created_at')
            ->take(10)
            ->get();

        $budgetSpent = Order::sum('total_amount') ?? 0;

        return view('dashboard.trust-head', compact(
            'pendingApprovals',
            'approvedByMe',
            'totalRequests',
            'sentToProvider',
            'recentApprovals',
            'budgetSpent'
        ));
    }

    /**
     * Admin dashboard - shows system overview.
     */
    private function adminDashboard($user): View
    {
        $totalRequests = StationaryRequest::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalDepartments = Department::count();

        $pendingRequests = StationaryRequest::where('status', RequestStatus::PENDING->value)->count();
        $inApprovalRequests = StationaryRequest::whereIn('status', [
            RequestStatus::HOD_APPROVED->value,
            RequestStatus::PRINCIPAL_APPROVED->value,
            RequestStatus::TRUST_APPROVED->value,
        ])->count();
        $sentToProviderRequests = StationaryRequest::where('status', RequestStatus::SENT_TO_PROVIDER->value)->count();
        $suppliedRequests = StationaryRequest::where('status', RequestStatus::SUPPLIED->value)->count();

        $ordersPending = Order::where('status', 'Pending')->count();
        $ordersConfirmed = Order::where('status', 'Confirmed')->count();
        $ordersDelivered = Order::where('status', 'Delivered')->count();

        $recentRequests = StationaryRequest::with(['requestedBy', 'department'])
            ->latest('created_at')
            ->take(10)
            ->get();

        $recentOrders = Order::with(['stationaryRequest', 'vendor'])
            ->latest('created_at')
            ->take(10)
            ->get();

        $departmentStats = Department::withCount('users')
            ->withCount(['stationaryRequests' => function ($query) {
                $query->where('status', RequestStatus::SUPPLIED->value);
            }])
            ->get();

        return view('dashboard.admin', compact(
            'totalRequests',
            'totalOrders',
            'totalUsers',
            'totalDepartments',
            'pendingRequests',
            'inApprovalRequests',
            'sentToProviderRequests',
            'suppliedRequests',
            'ordersPending',
            'ordersConfirmed',
            'ordersDelivered',
            'recentRequests',
            'recentOrders',
            'departmentStats'
        ));
    }

    /**
     * Provider dashboard - shows assigned orders and delivery tracking.
     */
    private function providerDashboard($user): View
    {
        $assignedOrders = Order::where('vendor_id', $user->vendor_id ?? 0)->count();

        $pendingDelivery = Order::where('vendor_id', $user->vendor_id ?? 0)
            ->where('status', 'Pending')
            ->count();

        $confirmedOrders = Order::where('vendor_id', $user->vendor_id ?? 0)
            ->where('status', 'Confirmed')
            ->count();

        $deliveredOrders = Order::where('vendor_id', $user->vendor_id ?? 0)
            ->where('status', 'Delivered')
            ->count();

        $recentOrders = Order::where('vendor_id', $user->vendor_id ?? 0)
            ->with(['stationaryRequest.department', 'items.product'])
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('dashboard.provider', compact(
            'assignedOrders',
            'pendingDelivery',
            'confirmedOrders',
            'deliveredOrders',
            'recentOrders'
        ));
    }
}
