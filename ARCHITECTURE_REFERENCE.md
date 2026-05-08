# Campus Store Management System - Architecture Reference

## Quick Reference Guide

### Enum Usage Examples

#### UserRole Enum
```php
use App\Enums\UserRole;

// Check role
$user->role === UserRole::HOD->value
UserRole::HOD->canApprove() // true
UserRole::TEACHER->canApprove() // false

// Get all approval roles
UserRole::approvalHierarchy() // [HOD, PRINCIPAL, TRUST_HEAD]

// Display in select
UserRole::toArray() // ['teacher' => 'Teacher', 'hod' => 'Head of Department', ...]
```

#### RequestStatus Enum
```php
use App\Enums\RequestStatus;

// Check status
$request->status === RequestStatus::PENDING->value

// Workflow operations
$request->status = RequestStatus::PENDING->nextInWorkflow(); // HOD_APPROVED
RequestStatus::PENDING->isActive() // true
RequestStatus::COMPLETED->isActive() // false

// For UI display
RequestStatus::HOD_APPROVED->label() // "HOD Approved"
RequestStatus::HOD_APPROVED->badgeClass() // "badge-blue"
```

### Trait Usage Examples

#### HasTimestamps Trait
```php
class User extends Model
{
    use HasTimestamps;
}

// Usage
User::createdToday()->get()
User::createdInLastDays(7)->get()
User::createdAfter('2024-01-01')->get()
$user->timeAgo() // "2 days ago"
```

#### HasWorkflowStatus Trait
```php
class StationaryRequest extends Model
{
    use HasWorkflowStatus;
}

// Usage
$pending = StationaryRequest::pending()->get()
$approved = StationaryRequest::approved()->get()
$active = StationaryRequest::active()->get()
$inPipeline = StationaryRequest::inApprovalPipeline()->get()
```

### Service Pattern Implementation

#### Creating a New Service

1. **Create Service Class**:
```php
namespace App\Services;

class UserService extends BaseService
{
    protected function initializeRepository(): void
    {
        $this->repository = new UserRepository();
    }
    
    protected function validate(array $data): void
    {
        // Add custom validation
        if (isset($data['email'])) {
            // Validate email format, uniqueness, etc.
        }
    }
    
    // Add custom business methods
    public function getUsersByRole(string $role)
    {
        return $this->repository->findAllBy('role', $role);
    }
}
```

2. **Create Repository Class**:
```php
namespace App\Repositories;

class UserRepository extends BaseRepository
{
    public function model(): Model
    {
        return new \App\Models\User();
    }
    
    // Add custom query methods
    public function findActiveUsers()
    {
        return $this->query()->where('is_active', true)->get();
    }
}
```

3. **Use in Controller**:
```php
class UserController extends Controller
{
    protected UserService $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return redirect()->route('users.show', $user);
    }
}
```

### Model Relationship Patterns (PHASE 2)

#### One-to-Many Example
```php
// User model
public function requests()
{
    return $this->hasMany(StationaryRequest::class, 'requested_by');
}

// StationaryRequest model
public function requester()
{
    return $this->belongsTo(User::class, 'requested_by');
}
```

#### Many-to-Many Example
```php
// Request model
public function items()
{
    return $this->hasMany(RequestItem::class);
}

// RequestItem model
public function product()
{
    return $this->belongsTo(Product::class);
}
```

### Livewire Component Pattern

```php
namespace App\Livewire;

use Livewire\Component;
use App\Services\StationaryRequestService;

class RequestList extends Component
{
    public function __construct(
        protected StationaryRequestService $requestService
    ) {}
    
    public function render()
    {
        $requests = $this->requestService->getAll(15);
        
        return view('livewire.request-list', [
            'requests' => $requests,
        ]);
    }
}
```

### Form Request Validation Pattern

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStationaryRequestRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }
}
```

### Authorization Policy Pattern (PHASE 3)

```php
namespace App\Policies;

class StationaryRequestPolicy
{
    public function view(User $user, StationaryRequest $request)
    {
        return $user->department_id === $request->department_id;
    }
    
    public function approve(User $user, StationaryRequest $request)
    {
        return $request->canBeApprovedBy($user);
    }
}
```

### Workflow Database Pattern (PHASE 2)

```php
// stationary_requests table
- id
- department_id (FK)
- requested_by (FK to users)
- title
- description
- status (enum: pending, hod_approved, principal_approved, ...)
- created_at
- updated_at

// approvals table
- id
- stationary_request_id (FK)
- approved_by (FK to users)
- approval_level (1=HOD, 2=PRINCIPAL, 3=TRUST_HEAD)
- comment
- approval_status (approved/rejected)
- created_at

// orders table
- id
- stationary_request_id (FK)
- vendor_id (FK)
- total_amount
- status
- created_at
- updated_at
```

### Event/Listener Pattern (PHASE 7)

```php
// Event
namespace App\Events;

class RequestApproved
{
    public function __construct(
        public StationaryRequest $request,
        public User $approvedBy
    ) {}
}

// Listener
namespace App\Listeners;

class SendApprovalNotification
{
    public function handle(RequestApproved $event)
    {
        $event->request->requester->notify(
            new RequestApprovedNotification($event->request)
        );
    }
}

// In Service
function approveRequest($id, $userId) {
    $request = $this->repository->find($id);
    $request->approve($userId);
    
    event(new RequestApproved($request, auth()->user()));
}
```

---

## Development Workflow

### Adding a New Feature

1. **Database Design**
   - Design migration with foreign keys
   - Run migration: `php artisan make:migration`
   - Define relationships

2. **Model & Repository**
   - Create Model:php artisan make:model
   - Create Repository extending BaseRepository
   - Add custom query methods

3. **Service Layer**
   - Create Service extending BaseService
   - Add business logic methods
   - Implement validation

4. **Controller/Actions**
   - Create Controller or Livewire action
   - Inject Service
   - Use FormRequest for validation

5. **Views/Components**
   - Create Blade components
   - Create Livewire components for interactivity
   - Use Flux components for UI

6. **Routes**
   - Add routes with proper naming
   - Use middleware for authorization
   - Group related routes

7. **Tests**
   - Create Feature test
   - Create Policy test
   - Create Service test

8. **Documentation**
   - Comment code clearly
   - Update API documentation
   - Add examples to this guide

---

**Remember**: 
- Keep controllers thin - logic goes in services
- Use repositories for all database queries
- Always use transactions for multi-step operations
- Validate early, validate often
- Test before committing
