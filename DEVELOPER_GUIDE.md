# Campus Store Management System - Developer Guide

## Project Summary

**Campus Store Management System** is a Laravel 10 application built with:
- PHP 8+
- MySQL Database
- Bootstrap 5 UI
- Eloquent ORM
- Laravel Fortify Authentication

---

## Quick File Reference

### Controllers
```
app/Http/Controllers/
├── DashboardController.php       # 6 role-based dashboards
├── RequestController.php          # Request CRUD (create, read, update, delete)
├── ApprovalController.php         # Approval workflow logic
├── ProductController.php          # Admin: Product management
└── Admin/
    ├── DepartmentController.php   # Admin: Department management
    └── UserController.php         # Admin: User management
```

### Models with Key Methods
```
app/Models/
├── User.php                       # hasMany(StationaryRequest), hasMany(Approval)
│   ├── isAdmin()
│   ├── isTeacher()
│   ├── isHOD()
│   ├── isPrincipal()
│   ├── isTrustHead()
│   └── isProvider()
│
├── StationaryRequest.php          # belongsTo(Department), hasMany(RequestItem)
│   ├── isPending()
│   ├── isHodApproved()
│   ├── isPrincipalApproved()
│   ├── isTrustApproved()
│   ├── isSentToProvider()
│   ├── isCompleted()
│   └── isRejected()
│
├── RequestItem.php                # belongsTo(StationaryRequest), belongsTo(Product)
├── Approval.php                   # belongsTo(StationaryRequest), belongsTo(User)
├── Department.php                 # hasMany(User), hasMany(StationaryRequest)
└── Product.php                    # hasMany(RequestItem)
```

### Migrations
```
database/migrations/
├── 0001_01_01_000000_create_users_table.php
├── 0001_01_01_000001_create_cache_table.php
├── 0001_01_01_000002_create_jobs_table.php
├── 2025_01_01_000000_create_departments_table.php
├── 2025_01_01_000001_create_products_table.php
├── 2025_01_02_000000_modify_users_table.php        # Adds role & department_id
├── 2025_01_02_000001_create_requests_table.php
├── 2025_01_02_000002_create_request_items_table.php
└── 2025_01_02_000003_create_approvals_table.php
```

### Views
```
resources/views/
├── layouts/app.blade.php                          # Main layout with sidebar
├── dashboards/
│   ├── admin.blade.php
│   ├── teacher.blade.php
│   ├── hod.blade.php
│   ├── principal.blade.php
│   ├── trust-head.blade.php
│   └── provider.blade.php
│
├── requests/
│   ├── index.blade.php    # List all requests
│   ├── create.blade.php   # Create request form with dynamic items
│   ├── show.blade.php     # View request & approval timeline
│   └── edit.blade.php     # Edit pending request
│
├── approvals/
│   └── show.blade.php     # Approval decision form
│
└── admin/
    ├── products/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── show.blade.php
    ├── departments/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── show.blade.php
    └── users/
        ├── index.blade.php
        ├── create.blade.php
        ├── edit.blade.php
        └── show.blade.php
```

### Middleware
```
app/Http/Middleware/
└── CheckRole.php                 # Validates user role(s)
                                   # Usage: ->middleware('role:admin,teacher')
```

### Routes
```
routes/
└── web.php                        # All application routes
    ├── Dashboard routes
    ├── Request routes (CRUD)
    ├── Approval routes
    ├── Admin routes (prefix: /admin)
    │   ├── Users resource
    │   ├── Departments resource
    │   └── Products resource
    └── Settings routes (from settings.php)
```

---

## Common Development Tasks

### Add a New User Role

1. **Update Database**
   ```php
   // In migration, modify enum:
   $table->enum('role', ['admin', 'teacher', 'hod', 'principal', 'trust_head', 'provider', 'new_role']);
   ```

2. **Add Helper Method to User Model**
   ```php
   public function isNewRole(): bool {
       return $this->role === 'new_role';
   }
   ```

3. **Add Route Protection**
   ```php
   Route::middleware('role:new_role')->group(function () {
       // routes here
   });
   ```

4. **Create Dashboard Method** (if needed)
   ```php
   // In DashboardController
   public function newRole() {
       // implementation
   }
   ```

### Add a New Request Status

1. **Update Database**
   ```php
   // In migration:
   $table->enum('status', ['pending', 'hod_approved', 'new_status', 'completed']);
   ```

2. **Add Method to StationaryRequest Model**
   ```php
   public function isNewStatus(): bool {
       return $this->status === 'new_status';
   }
   ```

3. **Update Blade Templates** (status display)
   ```blade
   @elseif($request->isNewStatus())
       <span class="badge" style="background-color: #color;">New Status</span>
   @endif
   ```

### Create a New Admin Feature

1. **Create Controller**
   ```bash
   php artisan make:controller Admin/FeatureController
   ```

2. **Add Routes** (in routes/web.php)
   ```php
   Route::resource('features', FeatureController::class);
   // Automatically creates index, create, store, show, edit, update, destroy
   ```

3. **Create Model** (if new entity)
   ```bash
   php artisan make:model Feature -m
   ```

4. **Create Views** (in resources/views/admin/features/)
   - index.blade.php
   - create.blade.php
   - edit.blade.php
   - show.blade.php

---

## Database Queries

### Get Pending Requests for HOD
```php
StationaryRequest::where('status', 'pending')
    ->where('department_id', Auth::user()->department_id)
    ->get();
```

### Get User's Requests
```php
Auth::user()->stationaryRequests();
```

### Get Approval History for Request
```php
$request->approvals()->with('approver')->get();
```

### Calculate Total Spent by Department
```php
StationaryRequest::where('department_id', $deptId)
    ->where('status', 'completed')
    ->sum('total_amount');
```

### Get Most Requested Products
```php
RequestItem::selectRaw('product_id, SUM(quantity) as total')
    ->groupBy('product_id')
    ->orderByDesc('total')
    ->limit(10)
    ->get();
```

---

## Environment Configuration

### .env Variables
```
APP_NAME=Campus Store
APP_ENV=local|production
APP_KEY=base64:xxxxx
APP_DEBUG=true|false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_store
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
```

---

## Testing Commands

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/RequestTest.php

# Run with coverage report
php artisan test --coverage

# Run feature tests only
php artisan test tests/Feature

# Run unit tests only
php artisan test tests/Unit
```

---

## Useful Artisan Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:refresh      # Rollback and run migrations
php artisan migrate:reset        # Rollback all migrations
php artisan migrate:rollback     # Rollback last batch
php artisan seed                 # Run seeders
php artisan db:seed --class=DatabaseSeeder

# Cache & Config
php artisan config:clear        # Clear config cache
php artisan cache:clear         # Clear application cache
php artisan route:clear         # Clear route cache
php artisan view:clear          # Clear compiled views
php artisan optimize:clear      # Clear optimization files

# Development
php artisan serve               # Start development server
php artisan tinker              # Interactive shell
php artisan make:controller ControllerName
php artisan make:model ModelName -m
php artisan make:migration migration_name

# Maintenance
php artisan down                # Put app in maintenance mode
php artisan up                  # Take app out of maintenance mode

# Other
php artisan key:generate        # Generate app key
php artisan storage:link        # Create storage symlink
php artisan queue:work          # Process queue jobs
```

---

## Form Handling

### Form Validation Rules
```php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,' . $user->id,
    'role' => 'required|in:admin,teacher,hod,principal,trust_head,provider',
    'department_id' => 'nullable|exists:departments,id',
    'password' => 'required|string|min:8|confirmed',
]);
```

### CSRF Protection
```blade
<form method="POST" action="{{ route('action') }}">
    @csrf
    <!-- form fields -->
</form>
```

### Error Display
```blade
@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endforeach
@endif

<!-- Or for individual fields -->
@error('email')
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
```

---

## Debugging Tips

### Check Authentication
```php
if (Auth::check()) {
    $user = Auth::user();
    echo $user->role;  // Check user role
}
```

### Log Information
```php
\Log::info('Debugging info', ['user' => Auth::user()]);
// Check in storage/logs/laravel.log
```

### Use Tinker
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->stationaryRequests()->count();
```

### Check Queries
```php
// Enable query logging
DB::enableQueryLog();

// Run code here

// View queries
dd(DB::getQueryLog());
```

---

## Performance Optimization

### N+1 Query Prevention
```php
// Bad
$requests = StationaryRequest::all();
foreach ($requests as $req) {
    echo $req->department->name;  // Query inside loop!
}

// Good
$requests = StationaryRequest::with('department')->get();
foreach ($requests as $req) {
    echo $req->department->name;  // No query inside loop
}
```

### Use Pagination
```php
// Return 15 items per page
$items = Item::paginate(15);

// In view
{{ $items->links() }}
```

### Add Database Indexes
```php
// In migration
$table->index('department_id');
$table->index('requested_by');
$table->index(['status', 'created_at']);
```

---

## Security Best Practices

1. **Always Sanitize Input**
   ```php
   $input = $request->input('field');  // Automatic sanitization
   ```

2. **Use Prepared Statements** (Eloquent does this)
   ```php
   // Secure
   User::where('email', $email)->first();
   
   // Avoid
   DB::select("SELECT * FROM users WHERE email = '$email'");
   ```

3. **Check Authorization**
   ```php
   $this->authorize('update', $model);
   // or use policies
   ```

4. **Validate File Uploads**
   ```php
   $file = $request->validate([
       'upload' => 'required|file|max:2048|mimes:pdf,doc,docx',
   ]);
   ```

---

## Deployment Checklist

- [ ] Set `.env` `APP_DEBUG=false`
- [ ] Set `.env` `APP_ENV=production`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Run migrations: `php artisan migrate`
- [ ] Seed data: `php artisan db:seed`
- [ ] Build assets: `npm run build`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Set file permissions properly
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up SSL certificate
- [ ] Configure mail service
- [ ] Run tests: `php artisan test`
- [ ] Set up monitoring/logging
- [ ] Backup database before deployment

---

## Support Resources

- Laravel Documentation: https://laravel.com/docs
- Bootstrap Documentation: https://getbootstrap.com/docs
- Font Awesome Icons: https://fontawesome.com/icons
- SQL Documentation: https://www.w3schools.com/sql/

---

**Version**: 1.0
**Last Updated**: February 23, 2026
