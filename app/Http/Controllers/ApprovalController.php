<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\StationaryRequest;
use App\Enums\RequestStatus;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;

class ApprovalController extends Controller
{
    /**
     * Display approvals pending for the current user.
     */
    public function pending(): View
    {
        $user = auth()->user();

        $query = StationaryRequest::query();

        // Filter based on user role and current status
        if ($user->isHOD()) {
            // HODs see pending requests in their department
            $query->where('department_id', $user->department_id)
                ->where('status', RequestStatus::PENDING->value);
        } elseif ($user->isPrincipal()) {
            // Principals see HOD-approved requests
            $query->where('status', RequestStatus::HOD_APPROVED->value);
        } elseif ($user->isTrustHead()) {
            // Trust heads see principal-approved requests
            $query->where('status', RequestStatus::PRINCIPAL_APPROVED->value);
        } elseif ($user->isAdmin()) {
            // Admins see all requests in approval pipeline
            $query->whereIn('status', [
                RequestStatus::PENDING->value,
                RequestStatus::HOD_APPROVED->value,
                RequestStatus::PRINCIPAL_APPROVED->value,
                RequestStatus::TRUST_APPROVED->value,
            ]);
        } else {
            // Non-approvers see nothing
            $query->whereRaw('1 = 0');
        }

        $approvals = $query->with([
            'requestedBy',
            'department',
            'items.product',
            'approvals.approvedBy'
        ])->latest('created_at')->paginate(15);

        return view('approvals.pending', compact('approvals'));
    }

    /**
     * Display completed approvals.
     */
    public function completed(): View
    {
        $user = auth()->user();

        // Get approvals made by the current user
        $approvals = Approval::where('approved_by', $user->id)
            ->with([
                'stationaryRequest.requestedBy',
                'stationaryRequest.department',
                'approvedBy'
            ])
            ->latest('created_at')
            ->paginate(15);

        return view('approvals.completed', compact('approvals'));
    }

    /**
     * Display all approvals for a request.
     */
    public function requestApprovals(StationaryRequest $stationaryRequest): View
    {
        $this->authorize('viewApprovals', $stationaryRequest);

        $stationaryRequest->load([
            'requestedBy',
            'department',
            'items.product',
            'approvals.approvedBy'
        ]);

        $approvals = $stationaryRequest->approvals()->with('approvedBy')->get();

        return view('approvals.history', compact('stationaryRequest', 'approvals'));
    }

    /**
     * Display approval statistics.
     */
    public function stats(): View
    {
        $user = auth()->user();

        $stats = [
            'total_approvals' => 0,
            'approved' => 0,
            'rejected' => 0,
            'pending_approval_count' => 0,
        ];

        if ($user->isHOD()) {
            $stats['total_approvals'] = Approval::whereHas('stationaryRequest', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            })->count();

            $stats['approved'] = Approval::where('status', 'approved')
                ->whereHas('stationaryRequest', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                })->count();

            $stats['rejected'] = Approval::where('status', 'rejected')
                ->whereHas('stationaryRequest', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                })->count();

            $stats['pending_approval_count'] = StationaryRequest::where('department_id', $user->department_id)
                ->where('status', RequestStatus::PENDING->value)
                ->count();

        } elseif ($user->isPrincipal()) {
            $stats['total_approvals'] = Approval::where('approved_by', $user->id)->count();
            $stats['approved'] = Approval::where('approved_by', $user->id)
                ->where('status', 'approved')
                ->count();
            $stats['rejected'] = Approval::where('approved_by', $user->id)
                ->where('status', 'rejected')
                ->count();
            $stats['pending_approval_count'] = StationaryRequest::where('status', RequestStatus::HOD_APPROVED->value)->count();

        } elseif ($user->isTrustHead()) {
            $stats['total_approvals'] = Approval::where('approved_by', $user->id)->count();
            $stats['approved'] = Approval::where('approved_by', $user->id)
                ->where('status', 'approved')
                ->count();
            $stats['rejected'] = Approval::where('approved_by', $user->id)
                ->where('status', 'rejected')
                ->count();
            $stats['pending_approval_count'] = StationaryRequest::where('status', RequestStatus::PRINCIPAL_APPROVED->value)->count();

        } elseif ($user->isAdmin()) {
            $stats['total_approvals'] = Approval::count();
            $stats['approved'] = Approval::where('status', 'approved')->count();
            $stats['rejected'] = Approval::where('status', 'rejected')->count();
            $stats['pending_approval_count'] = StationaryRequest::whereIn('status', [
                RequestStatus::PENDING->value,
                RequestStatus::HOD_APPROVED->value,
                RequestStatus::PRINCIPAL_APPROVED->value,
                RequestStatus::TRUST_APPROVED->value,
            ])->count();
        }

        return view('approvals.stats', compact('stats'));
    }

    /**
     * Display approval chain/workflow for a request.
     */
    public function workflow(StationaryRequest $stationaryRequest): View
    {
        $this->authorize('view', $stationaryRequest);

        $stationaryRequest->load([
            'requestedBy',
            'department',
            'approvals.approvedBy'
        ]);

        // Build workflow steps based on status
        $workflowSteps = $this->buildWorkflowSteps($stationaryRequest);

        return view('approvals.workflow', compact('stationaryRequest', 'workflowSteps'));
    }

    /**
     * Build workflow steps for display.
     */
    private function buildWorkflowSteps(StationaryRequest $stationaryRequest): array
    {
        $steps = [
            [
                'level' => 'Created',
                'role' => 'Teacher',
                'status' => 'completed',
                'date' => $stationaryRequest->created_at,
                'user' => $stationaryRequest->requestedBy->name,
                'notes' => null,
            ],
        ];

        // Get approval history
        foreach ($stationaryRequest->approvals as $approval) {
            $steps[] = [
                'level' => match ($approval->approval_level) {
                    1 => 'HOD Approval',
                    2 => 'Principal Approval',
                    3 => 'Trust Head Approval',
                    4 => 'Admin Approval',
                    default => 'Unknown',
                },
                'role' => match ($approval->approval_level) {
                    1 => 'HOD',
                    2 => 'Principal',
                    3 => 'Trust Head',
                    4 => 'Admin',
                    default => 'Unknown',
                },
                'status' => $approval->status === 'approved' ? 'completed' : 'rejected',
                'date' => $approval->created_at,
                'user' => $approval->approvedBy->name,
                'notes' => $approval->notes,
            ];
        }

        // Add current step if still in workflow
        if (!$stationaryRequest->isSupplied() && !$stationaryRequest->isRejected()) {
            $nextStep = match ($stationaryRequest->status) {
                RequestStatus::PENDING->value => [
                    'level' => 'HOD Approval',
                    'role' => 'HOD',
                ],
                RequestStatus::HOD_APPROVED->value => [
                    'level' => 'Principal Approval',
                    'role' => 'Principal',
                ],
                RequestStatus::PRINCIPAL_APPROVED->value => [
                    'level' => 'Trust Head Approval',
                    'role' => 'Trust Head',
                ],
                RequestStatus::TRUST_APPROVED->value => [
                    'level' => 'Admin Final Approval',
                    'role' => 'Admin',
                ],
                default => null,
            };

            if ($nextStep) {
                $steps[] = array_merge($nextStep, [
                    'status' => 'pending',
                    'date' => null,
                    'user' => 'Awaiting approval',
                    'notes' => null,
                ]);
            }
        }

        return $steps;
    }
}
