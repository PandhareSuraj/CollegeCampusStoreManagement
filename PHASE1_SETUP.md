# Campus Store Management System - PHASE 1 Setup Complete ✅

## Project Overview
A comprehensive Laravel 12 enterprise application for managing stationary/store requests in educational institutions with a multi-level approval workflow.

## Tech Stack Installed
- **Laravel**: 12.x
- **PHP**: 8.2+
- **Database**: PostgreSQL
- **Frontend Framework**: Livewire 4 + Flux UI
- **CSS Framework**: Tailwind CSS 4
- **Build Tool**: Vite
- **Authentication**: Laravel Fortify
- **Testing**: PHPUnit 11.x
- **Development**: Laravel Sail, Laravel Pail, Debugbar, Telescope

## Project Structure

```
app/
├── Enums/                      # Application enums
│   ├── UserRole.php           # User roles (teacher, hod, principal, etc.)
│   └── RequestStatus.php      # Request workflow statuses
├── Traits/                     # Reusable model traits
│   ├── HasTimestamps.php      # Timestamp-related scopes
│   └── HasWorkflowStatus.php  # Workflow status scopes
├── Services/                   # Business logic layer
│   └── BaseService.php        # Abstract service class
├── Repositories/              # Data access layer
│   └── BaseRepository.php     # Abstract repository class
├── Http/
│   └── Controllers/
├── Livewire/                  # Livewire components
│   └── Actions/
├── Models/
│   └── User.php
└── Providers/
    └── AppServiceProvider.php
```

## Configuration Files
- **`.env`**: Application environment variables (PostgreSQL configured)
- **`config/app.php`**: Main application configuration
- **`config/fortify.php`**: Authentication configuration
- **`config/database.php`**: Database connections
- **`config/session.php`**: Session driver (database)
- **`config/cache.php`**: Cache driver (database)
- **`config/queue.php`**: Queue driver (database)

## Enums Created

### UserRole Enum
Defines all user roles in the system:
- `TEACHER` - Creates requests
- `HOD` - First approval level
- `PRINCIPAL` - Second approval level
- `TRUST_HEAD` - Third approval level
- `ADMIN` - System administrator
- `PROVIDER` - Vendor/supplier

**Methods**: `label()`, `description()`, `canApprove()`, `approvalHierarchy()`

### RequestStatus Enum
Defines request workflow statuses:
- `PENDING` - Awaiting HOD approval
- `HOD_APPROVED` - Approved by Head of Department
- `PRINCIPAL_APPROVED` - Approved by Principal
- `TRUST_APPROVED` - Approved by Trust Head
- `SENT_TO_PROVIDER` - Sent to vendor
- `COMPLETED` - Fulfilled
- `REJECTED` - Rejected at any stage

**Methods**: `label()`, `badgeColor()`, `nextInWorkflow()`, `isActive()`, `approvalLevel()`

## Reusable Traits

### HasTimestamps
Provides timestamp-related query scopes:
- `createdToday()` - Today's records
- `createdInLastDays()` - Recent records
- `createdAfter()` - Records after a date
- `timeAgo()` - Human-readable time offset

### HasWorkflowStatus
Provides workflow status query scopes:
- `byStatus()` - Filter by status
- `pending()`, `approved()`, `active()` - Status-specific filters
- `inApprovalPipeline()` - Records in workflow

## Service Layer Architecture

### BaseService
Abstract service class for all business services:
- CRUD operations: `create()`, `update()`, `delete()`
- Data retrieval: `getAll()`, `getById()`
- Data validation: `validate()`
- Exception handling

**Implementation Pattern**:
```php
class UserService extends BaseService
{
    protected function initializeRepository(): void
    {
        $this->repository = new UserRepository();
    }
}
```

## Repository Pattern Architecture

### BaseRepository
Abstract repository for data access:
- Basic CRUD: `create()`, `update()`, `delete()`
- Query methods: `all()`, `paginate()`, `find()`
- Filter methods: `findBy()`, `findAllBy()`
- Utility methods: `exists()`, `count()`

**Implementation Pattern**:
```php
class UserRepository extends BaseRepository
{
    public function model(): Model
    {
        return new User();
    }
}
```

## Next Steps (PHASE 2)

Database architecture and migrations will be created with the following order:
1. `colleges` - Educational institution records
2. `departments` - Campus departments
3. `users` - System users (with role column)
4. `vendors` - Suppliers/providers
5. `products` - Stationary items
6. `stationary_requests` - Request records
7. `request_items` - Item details in requests
8. `approvals` - Approval workflow records
9. `orders` - Purchase orders
10. `order_items` - Items in orders
11. `notifications` - System notifications
12. `activity_logs` - Audit trail
13. `settings` - System configuration

## Environment Variables
```bash
APP_NAME="Campus Store Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=CollegeCampusStoreManagement

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

## Running the Application

### Start Development Server
```bash
php artisan serve
```

### Run Frontend Build
```bash
npm run dev
```

### Run Database Migrations
```bash
php artisan migrate
```

### Run Tests
```bash
php artisan test
```

### Dev Command (concurrent development)
```bash
composer run dev
```

## Development Guidelines

1. **Services**: All business logic goes in `app/Services/`
2. **Repositories**: Data access through `app/Repositories/`
3. **Models**: Keep models lightweight, use scopes from traits
4. **Validation**: Use FormRequest classes from `app/Http/Requests/`
5. **Authorization**: Use Policies from `app/Policies/`
6. **Components**: Livewire components in `app/Livewire/`
7. **Database**: Use migrations, maintain foreign key constraints
8. **Testing**: Feature tests in `tests/Feature/`, Unit tests in `tests/Unit/`

## Security Checklist
- ✅ CSRF protection enabled
- ✅ Input validation via FormRequests
- ✅ Role-based authorization
- ✅ Database transactions for critical operations
- ✅ Soft deletes for data preservation
- ⏳ Policies for model authorization (PHASE 3)
- ⏳ Encryption for sensitive data (PHASE 7)

## References
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Livewire 4 Documentation](https://livewire.laravel.com)
- [Flux UI Documentation](https://flux.laravel.com)
- [Fortify Documentation](https://laravel.com/docs/12.x/fortify)
- [PHPUnit Documentation](https://phpunit.de)

---

**Phase 1 Status**: ✅ COMPLETE
**Next Phase**: Database Architecture & Migrations (PHASE 2)
**Estimated Timeline**: Ready for PHASE 2 development
