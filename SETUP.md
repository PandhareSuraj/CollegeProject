# Campus Store Management System - Setup Guide

## Quick Start (5 minutes)

### 1. Install Dependencies
```bash
cd /home/suraj/Documents/CampusStoreManagementSystem
composer install
npm install
```

### 2. Set Up Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:
```
DB_DATABASE=campus_store
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

### 3. Create & Migrate Database
```bash
# Create database first
mysql -u root -p -e "CREATE DATABASE campus_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Then migrate tables
php artisan migrate
```

### 4. Build Front-end Assets
```bash
npm run dev
```

### 5. Start Development Server
```bash
php artisan serve
```

Access at: `http://localhost:8000`

---

## Database Setup

The system uses MySQL. Tables created:
- users (with role enum and department_id)
- departments
- products
- requests (stationary requests)
- request_items (individual items in requests)
- approvals (approval workflow tracking)

All migrations have been provided and are ready to run.

---

## Testing the Application

### Default Users to Create (manually or via tinker)

#### Admin
```
Name: Admin User
Email: admin@campus.edu
Password: password
Role: admin
```

#### Department Head (HOD)
```
Name: Dr. John HOD
Email: hod@campus.edu
Password: password
Role: hod
Department: Computer Science
```

#### Principal
```
Name: Dr. Principal
Email: principal@campus.edu
Password: password
Role: principal
```

#### Trust Head
```
Name: Mr. Trust Head
Email: trust@campus.edu
Password: password
Role: trust_head
```

#### Teacher
```
Name: Mrs. Teacher
Email: teacher@campus.edu
Password: password
Role: teacher
Department: Computer Science
```

#### Provider
```
Name: Supply Provider
Email: provider@campus.edu
Password: password
Role: provider
```

### Create Users via Tinker
```bash
php artisan tinker

# Create Department
\App\Models\Department::create(['name' => 'Computer Science', 'description' => 'Computer Science Department']);

# Create Admin
\App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@campus.edu',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);

# Create HOD
\App\Models\User::create([
    'name' => 'Dr. John HOD',
    'email' => 'hod@campus.edu',
    'password' => bcrypt('password'),
    'role' => 'hod',
    'department_id' => 1
]);

# Create Teacher
\App\Models\User::create([
    'name' => 'Mrs. Teacher',
    'email' => 'teacher@campus.edu',
    'password' => bcrypt('password'),
    'role' => 'teacher',
    'department_id' => 1
]);

# Create Products
\App\Models\Product::create([
    'name' => 'Notebook A4',
    'description' => 'Ruled A4 Notebook',
    'stock_quantity' => 100,
    'price' => 25.00
]);

exit()
```

---

## Directory Structure Overview

```
app/
├── Http/Controllers/          # Request handlers
├── Models/                    # Eloquent models with relationships
└── Http/Middleware/           # Role checking middleware

database/migrations/           # Database table definitions
resources/views/               # Blade templates (Bootstrap UI)
routes/web.php                # All URL routes

bootstrap/app.php             # Middleware configuration
```

---

## Key Files Modified/Created

### Controllers
- `DashboardController.php` - 6 role-specific dashboards
- `RequestController.php` - Request CRUD operations
- `ApprovalController.php` - Request approval workflow
- `ProductController.php` - Product management (Admin)
- `Admin/DepartmentController.php` - Department management
- `Admin/UserController.php` - User management

### Models
- `StationaryRequest.php` - Main request entity
- `RequestItem.php` - Items within a request
- `Approval.php` - Approval tracking
- `Department.php` - Department organization
- `Product.php` - Products/items
- `User.php` - Updated with relationships

### Migrations
- 6 new migration files for:
  - Departments table
  - Products table
  - Users table modifications (role + department_id)
  - Requests table
  - Request items table
  - Approvals table

### Views (Blade Templates)
- 24 view files organized by functionality
- Bootstrap 5 responsive design
- Font Awesome icons
- Custom CSS styling

### Middleware
- `CheckRole.php` - Validates user roles

### Routes
- RESTful routes for all resources
- Role-based middleware protection
- Named routes for easy navigation

---

## Workflow Example

### Teacher Creates Request
1. Login as teacher@campus.edu
2. Click "Create Request"
3. Add stationary items and quantities
4. Submit
5. Request status: `pending`

### HOD Reviews & Approves
1. Login as hod@campus.edu
2. Dashboard shows "Pending Approvals"
3. Click "Review" on request
4. View items and total amount
5. Click "Approve" and submit
6. Request status: `hod_approved`

### Principal Reviews
1. Login as principal@campus.edu
2. Click pending request
3. Review HOD's approval
4. Approve/Reject
5. Request status: `principal_approved`

### Trust Head Reviews
1. Login as trust@campus.edu
2. Review request
3. Approve/Reject
4. Request status: `trust_approved`

### Provider Supplies
1. Login as provider@campus.edu
2. View requests ready for supply
3. Click "Supplied" button
4. Product stock automatically reduced
5. Request status: `completed`

---

## Admin Functions

### User Management
- Create users with specific roles
- Assign to departments
- Update user information
- Delete users

### Department Management
- Create departments
- View users in each department
- View requests per department
- Edit department details

### Product Management
- Add new products with price and stock
- Update product information
- View stock levels
- Delete products (if not used)

---

## Important Notes

1. **Migrations**: Run `php artisan migrate` after cloning
2. **User Roles**: Required for access control - always set a role
3. **Departments**: Teachers and HODs must be assigned to a department
4. **Stock Management**: Automatic reduction when provider marks as supplied
5. **Approvals**: Each role can see only relevant requests

---

## Troubleshooting

### 502 Bad Gateway
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Database connection error
- Check `.env` database credentials
- Ensure database exists
- Run `mysql` to verify connection

### Migrations failed
```bash
php artisan migrate:reset
php artisan migrate
```

### Assets not loading
```bash
npm install
npm run dev
php artisan optimize
```

---

## Support

For additional help or issues, refer to the main README.md file or check Laravel documentation at laravel.com

---

**Created**: February 23, 2026
