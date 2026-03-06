# Campus Store Management System - Role System Documentation

## Overview
This document outlines the complete role hierarchy and structure for the Campus Store Management System. The system uses 6 distinct roles with specific permissions and responsibilities.

---

## Role Hierarchy

```
Trust Head (Institutional Level)
    ├── Principal (College Level)
    │   ├── HOD (Department Level)
    │   │   └── Teacher (Individual Faculty)
    │   └── Teacher
    └── Provider (Vendor Management)

Admin (System-wide Management)
```

---

## Detailed Role Definitions

### 1. **ADMIN** (`admin`)
**System Administrator**

- **Access Level:** Global (All institutions, all departments)
- **Responsibilities:**
  - Manage all system users and accounts
  - Configure system settings and parameters
  - Manage institutions (Colleges/Sansthas)
  - Add/remove all roles and users
  - View all reports and analytics
  - System maintenance and updates

- **In Code:**
  ```php
  User::ROLE_ADMIN  // Constant
  $user->isAdmin()  // Check method
  'admin'           // Enum value in users table
  ```

---

### 2. **TEACHER** (`teacher`)
**Faculty Member / Requestor**

- **Access Level:** Self and own department
- **Responsibilities:**
  - Submit stationary requests for departmental needs
  - View status of own submitted requests
  - View department-level notifications
  - Access course/teaching materials

- **Default Role:** New users are created as Teachers if no role is specified
- **Features:**
  - Can create stationary requests
  - Can view own request history
  - Receive notifications on request status

- **In Code:**
  ```php
  User::ROLE_TEACHER  // Constant
  $user->isTeacher()  // Check method
  'teacher'           // Enum value in users table
  ```

---

### 3. **HOD** (`hod`)
**Head of Department**

- **Access Level:** Department level
- **Responsibilities:**
  - Approve or reject departmental stationary requests
  - Manage department budget and resources
  - View all requests from their department
  - Generate department-level reports
  - Manage departmental policies

- **Features:**
  - Approve/reject requests from department members
  - View all department requests and analytics
  - Set department spending limits
  - Manage equipment and resources

- **In Code:**
  ```php
  User::ROLE_HOD  // Constant
  $user->isHOD()  // Check method
  'hod'           // Enum value in users table
  ```

---

### 4. **PRINCIPAL** (`principal`)
**College Principal**

- **Access Level:** College/Institution level
- **Responsibilities:**
  - Approve high-value departmental requests
  - Oversee all departments within the college
  - Approve/escalate requests to trust head if needed
  - Generate college-level reports and analytics
  - Manage college-wide policies and budgets

- **Features:**
  - View and approve all college requests
  - See all department requests
  - Set college-wide spending policies
  - Generate comprehensive college reports
  - Can override HOD decisions if needed

- **In Code:**
  ```php
  User::ROLE_PRINCIPAL  // Constant
  $user->isPrincipal()  // Check method
  'principal'           // Enum value in users table
  ```

---

### 5. **TRUST_HEAD** (`trust_head`)
**Trust/Organization Head**

- **Access Level:** Multi-institutional (all colleges in trust)
- **Responsibilities:**
  - Oversee multiple colleges/institutions
  - Approve organization-wide major purchases
  - Manage institutional policies and procedures
  - Strategic planning and budgeting
  - Generate organization-wide analytics

- **Features:**
  - View and manage all institutions
  - Approve/reject escalated requests
  - Set organization policies
  - Cross-institutional analytics and reporting
  - Manage inter-institutional resource sharing

- **In Code:**
  ```php
  User::ROLE_TRUST_HEAD  // Constant
  $user->isTrustHead()   // Check method
  'trust_head'           // Enum value in users table
  ```

---

### 6. **PROVIDER** (`provider`)
**Vendor/Supplier**

- **Access Level:** Vendor management (order fulfillment)
- **Responsibilities:**
  - Manage product catalogs and pricing
  - Upload and update product information
  - Fulfill approved orders
  - Manage deliveries
  - Handle invoicing and billing

- **Features:**
  - View approved orders assigned to them
  - Update inventory and product information
  - Mark orders as delivered
  - Generate billing/invoice reports
  - Communicate delivery status

- **In Code:**
  ```php
  User::ROLE_PROVIDER  // Constant
  $user->isProvider()  // Check method
  'provider'           // Enum value in users table
  ```

---

## Using Role Constants in Code

Instead of using hardcoded strings, always use the constants defined in the User model:

### Good Practice:
```php
// ✓ CORRECT - Using constants
if ($user->role === User::ROLE_ADMIN) {
    // User is admin
}

if ($user->isAdmin()) {
    // User is admin
}

$allowedRoles = User::ROLES;  // Get all roles
```

### Avoid:
```php
// ✗ WRONG - Hardcoded strings
if ($user->role === 'admin') {
    // Error prone, no IDE support
}

$roles = ['admin', 'teacher', 'hod'];  // Duplicated strings
```

---

## Role Seeding

The system automatically seeds all roles on database migration using the `RoleSeeder` class located at:
```
database/seeders/RoleSeeder.php
```

Each role includes:
- **name**: Human-readable role name
- **slug**: Machine-readable identifier (matches user.role enum)
- **description**: What this role does
- **permissions**: JSON array of allowed actions

---

## Database Structure

### Users Table Columns (Relevant to Roles)
```
- id: bigint
- name: varchar
- email: varchar (unique)
- role: enum('admin','teacher','hod','principal','trust_head','provider')
- department_id: bigint (nullable, foreign key to departments)
- created_at: timestamp
- updated_at: timestamp
```

### Roles Table (Metadata)
```
- id: bigint
- name: varchar (e.g., "Administrator")
- slug: varchar (e.g., "admin")
- description: text
- permissions: json
- created_at: timestamp
- updated_at: timestamp
```

---

## Role Check Methods

All available methods on User model:

```php
$user->isAdmin()       // Check if admin
$user->isTeacher()     // Check if teacher
$user->isHOD()         // Check if HOD
$user->isPrincipal()   // Check if Principal
$user->isTrustHead()   // Check if Trust Head
$user->isProvider()    // Check if Provider
```

---

## Request Approval Workflow

1. **TEACHER** submits a request
   ↓
2. **HOD** reviews and approves/rejects departmental requests
   ↓
3. **PRINCIPAL** reviews and approves high-value requests
   ↓
4. **TRUST_HEAD** handles organization-wide approvals and escalations
   ↓
5. **PROVIDER** fulfills approved orders

---

## Authorization Policies

Authorization policies are defined in:
```
app/Policies/
```

Each model has its own policy file that defines who can create, read, update, and delete records based on their role.

---

## Migration Files

Key migration files for role management:
- `2026_02_27_000000_create_roles_table.php` - Creates roles metadata table
- `2026_03_02_000006_update_users_table_add_department_and_role.php` - Adds role and department to users table

---

## Example Usage

### Creating a user with specific role:
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'role' => User::ROLE_TEACHER,
    'department_id' => $department->id,
]);
```

### Updating user role:
```php
$user->update(['role' => User::ROLE_HOD]);
```

### Checking multiple roles:
```php
if ($user->role === User::ROLE_PRINCIPAL || $user->role === User::ROLE_TRUST_HEAD) {
    // User is principal or trust head
}

// Or using in array:
if (in_array($user->role, [User::ROLE_PRINCIPAL, User::ROLE_TRUST_HEAD])) {
    // User is principal or trust head
}
```

### Getting all possible roles:
```php
foreach (User::ROLES as $role) {
    echo $role;  // admin, teacher, hod, principal, trust_head, provider
}
```

---

## Additional Resources

- Role Constants: `app/Models/User.php`
- Role Seeder: `database/seeders/RoleSeeder.php`
- Policies: `app/Policies/`
- Migrations: `database/migrations/`
