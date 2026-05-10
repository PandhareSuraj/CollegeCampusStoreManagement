<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangeUserRoleRequest;
use App\Http\Requests\AssignDepartmentRequest;
use App\Models\User;
use App\Models\Department;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $query = User::with('department')
            ->orderBy('created_at', 'desc');

        // Filter for HODs - can only see their department users
        $user = auth()->user();
        if ($user->isHOD()) {
            $query->where('department_id', $user->department_id);
        }

        $users = $query->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $this->authorize('create', User::class);

        $departments = Department::all();
        $roles = UserRole::cases();

        return view('users.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'department_id' => $validated['department_id'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()->route('users.show', $user)
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $this->authorize('view', $user);

        $user->load('department');

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        $departments = Department::all();

        return view('users.edit', compact('user', 'departments'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        if (isset($validated['department_id'])) {
            $user->department_id = $validated['department_id'];
        }
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }
        if (isset($validated['address'])) {
            $user->address = $validated['address'];
        }

        $user->save();

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Show form for changing user role.
     */
    public function changeRoleForm(User $user): View
    {
        $this->authorize('changeRole', $user);

        $roles = UserRole::cases();

        return view('users.change-role', compact('user', 'roles'));
    }

    /**
     * Change user role.
     */
    public function changeRole(ChangeUserRoleRequest $request, User $user): RedirectResponse
    {
        $this->authorize('changeRole', $user);

        $validated = $request->validated();

        $user->update(['role' => $validated['role']]);

        return redirect()->route('users.show', $user)
            ->with('success', 'User role changed successfully.');
    }

    /**
     * Show form for assigning department.
     */
    public function assignDepartmentForm(User $user): View
    {
        $this->authorize('assignDepartment', $user);

        $departments = Department::all();

        return view('users.assign-department', compact('user', 'departments'));
    }

    /**
     * Assign department to user.
     */
    public function assignDepartment(AssignDepartmentRequest $request, User $user): RedirectResponse
    {
        $this->authorize('assignDepartment', $user);

        $validated = $request->validated();

        $user->update(['department_id' => $validated['department_id']]);

        return redirect()->route('users.show', $user)
            ->with('success', 'Department assigned successfully.');
    }

    /**
     * Show current user profile.
     */
    public function profile(): View
    {
        $user = auth()->user();
        $user->load('department');

        return view('users.profile', compact('user'));
    }

    /**
     * Update current user profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }
        if (isset($validated['address'])) {
            $user->address = $validated['address'];
        }

        if (!empty($validated['new_password'])) {
            if (!password_verify($validated['current_password'] ?? '', $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = bcrypt($validated['new_password']);
        }

        $user->save();

        return redirect()->route('users.profile')
            ->with('success', 'Profile updated successfully.');
    }
}
