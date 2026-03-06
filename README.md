<<<<<<< HEAD
# CampusStoreManagement
=======
# Campus Store Management System

A Laravel 10 application for managing stationary product requests within a college campus with an approval workflow.

## Project Overview

This system manages stationary product requests inside a college campus with a multi-step approval process:

1. **Request Creation**: Teachers/Department Members submit stationary requests
2. **Approval Workflow**: 
   - HOD approves (department-level)
   - Principal approves (institution-level)
   - Trust Head approves (trust-level)
3. **Provider Supply**: After all approvals, the Provider supplies products
4. **Completion**: Department receives items and request is marked complete

## User Roles & Permissions

### 1. **Admin**
- Manage all users (create, edit, delete)
- Manage departments
- Manage products (create, stock management)
- View system reports and analytics
- Approve requests as final admin approval

### 2. **Teacher**
- Create new stationary requests
- Add multiple products to requests
- Edit/delete pending requests
- View request status and approval timeline
- View personal request history

### 3. **HOD (Head of Department)**
- View department-specific requests
- Approve or reject requests (first approval level)
- Add remarks during approval
- Cannot approve their own department's requests if both roles

### 4. **Principal**
- View all requests (after HOD approval)
- Approve or reject HOD-approved requests
- Add remarks
- Second approval level

### 5. **Trust Head**
- View all requests (after Principal approval)
- Approve or reject Principal-approved requests
- Add remarks
- Third approval level

### 6. **Provider**
- View approved requests ready for supply
- Mark requests as supplied/completed
- Automatic stock reduction when marking products as supplied

## Database Tables

### 1. **users**
```
- id (Primary Key)
- name (string)
- email (string, unique)
- password (string, hashed)
- role (enum: admin, teacher, hod, principal, trust_head, provider)
- department_id (foreign key, nullable)
- external_app_id (nullable)
- two_factor_secret (nullable)
- two_factor_recovery_codes (nullable)
- remember_token (nullable)
- email_verified_at (nullable)
- timestamps
```

### 2. **departments**
```
- id (Primary Key)
- name (string, unique)
- description (text, nullable)
- timestamps
```

### 3. **products**
```
- id (Primary Key)
- name (string)
- description (text, nullable)
- stock_quantity (integer)
- price (decimal 10,2)
- timestamps
```

### 4. **requests**
```
- id (Primary Key)
- department_id (foreign key → departments)
- requested_by (foreign key → users)
- status (enum: pending, hod_approved, principal_approved, trust_approved, sent_to_provider, completed, rejected)
- total_amount (decimal 12,2)
- timestamps
```

### 5. **request_items**
```
- id (Primary Key)
- request_id (foreign key → requests)
- product_id (foreign key → products)
- quantity (integer)
- price (decimal 10,2)
- subtotal (decimal 12,2)
- timestamps
```

### 6. **approvals**
```
- id (Primary Key)
- request_id (foreign key → requests)
- approved_by (foreign key → users)
- role (enum: hod, principal, trust_head, admin)
- status (enum: pending, approved, rejected)
- remarks (text, nullable)
- timestamps
```

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js (for Laravel Mix/Vite)

### Step 1: Clone the Repository
```bash
cd /home/suraj/Documents/CampusStoreManagementSystem
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_store
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Run Migrations
```bash
php artisan migrate
```

### Step 5: Create Sample Data (Optional)
```bash
php artisan tinker
# Create departments, products, and users as needed
```

### Step 6: Build Assets
```bash
npm run dev
# or for production
npm run build
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000`

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php         # Role-based dashboards
│   │   ├── RequestController.php           # CRUD operations for requests
│   │   ├── ApprovalController.php          # Approval workflow logic
│   │   ├── ProductController.php           # Admin: Product management
│   │   └── Admin/
│   │       ├── DepartmentController.php    # Admin: Department management
│   │       └── UserController.php          # Admin: User management
│   └── Middleware/
│       └── CheckRole.php                   # Role-based access control
├── Models/
│   ├── User.php                            # User model with relationships
│   ├── Department.php                      # Department model
│   ├── Product.php                         # Product model
│   ├── StationaryRequest.php               # Request model (main entity)
│   ├── RequestItem.php                     # Item in request
│   └── Approval.php                        # Approval record
├── Actions/
│   └── Fortify/                            # Authentication actions
└── Providers/
    ├── AppServiceProvider.php
    └── FortifyServiceProvider.php

database/
├── migrations/                             # All database migrations
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2025_01_01_000000_create_departments_table.php
│   ├── 2025_01_01_000001_create_products_table.php
│   ├── 2025_01_02_000000_modify_users_table.php
│   ├── 2025_01_02_000001_create_requests_table.php
│   ├── 2025_01_02_000002_create_request_items_table.php
│   └── 2025_01_02_000003_create_approvals_table.php
├── factories/
│   └── UserFactory.php
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── layouts/
│   └── app.blade.php                       # Main layout
├── dashboards/
│   ├── admin.blade.php
│   ├── teacher.blade.php
│   ├── hod.blade.php
│   ├── principal.blade.php
│   ├── trust-head.blade.php
│   └── provider.blade.php
├── requests/
│   ├── index.blade.php                     # List all requests
│   ├── create.blade.php                    # Create new request
│   ├── show.blade.php                      # View request details
│   └── edit.blade.php                      # Edit request
├── approvals/
│   └── show.blade.php                      # Approval form
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

routes/
├── web.php                                 # All application routes
├── settings.php                            # Settings routes
└── console.php                             # Console commands
```

## Routes & Endpoints

### Authentication Routes
```
POST   /login                      - Login
POST   /logout                     - Logout
GET    /register                   - Register page
POST   /register                   - Register user
```

### Dashboard Routes
```
GET    /dashboard                  - Role-based dashboard
```

### Request Routes
```
GET    /requests                   - List all requests
GET    /requests/create            - Create request form (Teacher)
POST   /requests                   - Store new request (Teacher)
GET    /requests/{id}              - View request details
GET    /requests/{id}/edit         - Edit request form (Teacher, pending only)
PUT    /requests/{id}              - Update request (Teacher, pending only)
DELETE /requests/{id}              - Delete request (Teacher, pending only)
```

### Approval Routes
```
GET    /approvals/{id}             - Approval form (HOD, Principal, Trust Head, Admin)
POST   /approvals/{id}             - Submit approval (HOD, Principal, Trust Head, Admin)
POST   /requests/{id}/supplied     - Mark as supplied (Provider)
```

### Admin Routes (prefix: /admin)
```
# Users
GET    /admin/users                - List all users
GET    /admin/users/create         - Create user form
POST   /admin/users                - Store new user
GET    /admin/users/{id}           - View user details
GET    /admin/users/{id}/edit      - Edit user form
PUT    /admin/users/{id}           - Update user
DELETE /admin/users/{id}           - Delete user

# Departments
GET    /admin/departments          - List all departments
GET    /admin/departments/create   - Create department form
POST   /admin/departments          - Store new department
GET    /admin/departments/{id}     - View department details
GET    /admin/departments/{id}/edit - Edit department form
PUT    /admin/departments/{id}     - Update department
DELETE /admin/departments/{id}     - Delete department

# Products
GET    /admin/products             - List all products
GET    /admin/products/create      - Create product form
POST   /admin/products             - Store new product
GET    /admin/products/{id}        - View product details
GET    /admin/products/{id}/edit   - Edit product form
PUT    /admin/products/{id}        - Update product
DELETE /admin/products/{id}        - Delete product
```

## Key Features

### 1. **Multi-Level Approval Workflow**
- Requests flow through: Pending → HOD Approval → Principal Approval → Trust Head Approval → Provider
- Each role can approve/reject with remarks
- Rejection stops the workflow
- Approval history is tracked

### 2. **Role-Based Access Control**
- Middleware checks user roles before allowing access
- Routes protected with role verification
- Role-specific dashboards showing relevant metrics

### 3. **Dynamic Dashboard**
- Each role sees a customized dashboard
- Real-time statistics (pending, approved, completed counts)
- Quick action buttons based on role

### 4. **Request Management**
- Teachers can create requests with multiple items
- Dynamic item additions in forms
- Automatic total calculation
- Status tracking throughout workflow

### 5. **Product Stock Management**
- Admin manages product inventory
- Stock automatically reduced when provider marks items as supplied
- Stock status indicator (color-coded)

### 6. **Department Management**
- Create/manage departments
- Assign users to departments
- View department-specific statistics

## Usage Workflow

### Creating a Request (Teacher)
1. Navigate to "Create Request" in sidebar
2. Add products and quantities
3. System calculates total automatically
4. Submit request
5. Request goes to HOD for approval

### Approving a Request (HOD)
1. View pending requests in dashboard
2. Click "Review" on a request
3. View request details and items
4. Choose to Approve or Reject
5. Add optional remarks
6. Submit decision
7. If approved, request moves to Principal

### Tracking Progress (Teacher)
1. View request in "My Requests"
2. See approval timeline
3. View remarks from each approver
4. Track status from submission to completion

### Supplying Products (Provider)
1. View approved requests ready for supply
2. Click request details
3. Click "Mark as Supplied"
4. System reduces product stock
5. Request marked as "Completed"

## Middleware Details

### CheckRole Middleware
- Located at: `app/Http/Middleware/CheckRole.php`
- Checks if authenticated user has required role(s)
- Takes multiple roles as parameters
- Usage: `->middleware('role:admin,teacher')`

## Models & Relationships

### User Model
```php
- belongsTo(Department)
- hasMany(StationaryRequest, 'requested_by')
- hasMany(Approval, 'approved_by')
```

### Department Model
```php
- hasMany(User)
- hasMany(StationaryRequest)
```

### StationaryRequest Model
```php
- belongsTo(Department)
- belongsTo(User, 'requested_by')
- hasMany(RequestItem)
- hasMany(Approval)
```

### RequestItem Model
```php
- belongsTo(StationaryRequest, 'request_id')
- belongsTo(Product)
```

### Approval Model
```php
- belongsTo(StationaryRequest, 'request_id')
- belongsTo(User, 'approved_by')
```

## Testing

Run tests with:
```bash
php artisan test
```

## Configuration

### Environment Variables
```
APP_NAME="Campus Store Management System"
APP_ENV=local
APP_KEY=             # Generated with php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_store
DB_USERNAME=root
DB_PASSWORD=
```

## Security Considerations

1. **Password Hashing**: All passwords are hashed using bcrypt
2. **Role-Based Access**: Every action is checked for proper role
3. **CSRF Protection**: All forms include CSRF tokens
4. **SQL Injection Prevention**: Uses Eloquent ORM
5. **Mass Assignment Protection**: Fillable attributes defined on models

## Performance Optimization

1. **Eager Loading**: Relationships are eager-loaded to avoid N+1 queries
2. **Pagination**: Lists are paginated (15 items per page)
3. **Indexes**: Foreign keys indexed in database
4. **Caching**: Session-based data cached

## Troubleshooting

### Issue: Migrations fail
```bash
php artisan migrate:reset
php artisan migrate
```

### Issue: Role middleware not working
- Check bootstrap/app.php middleware registration
- Verify user role in database

### Issue: Assets not loading
```bash
npm run dev
# or
php artisan asset:publish
```

## Future Enhancements

1. Email notifications on approval status changes
2. Request templates for common items
3. Batch request creation
4. Export reports to PDF/Excel
5. Advanced filtering and search
6. Request cancellation workflow
7. Budget tracking and limits
8. Request scheduling/planning
9. Supplier management
10. Mobile application

## Support & Contribution

For issues or contributions, please contact the development team.

## License

This project is proprietary software for Campus Store Management.

---

**Last Updated**: February 23, 2026
>>>>>>> 208a175 (Initial commit)
