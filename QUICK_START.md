# Quick Start Guide - Campus Store Management System

## ⚡ Get Running in 5 Minutes

### Prerequisites
- PHP 8.2+ (`php --version`)
- PostgreSQL installed and running
- Composer installed (`composer --version`)
- Git (optional, for version control)

### Step 1: Setup Database (2 minutes)

```bash
# Navigate to project
cd /home/suraj/Documents/CampusStoreManagementSystem

# Create database (from PostgreSQL terminal or pgAdmin)
createdb campus_store_db
# OR using psql:
psql -U postgres -c "CREATE DATABASE campus_store_db;"

# Configure .env with your database credentials
nano .env
# Update these lines:
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=campus_store_db
# DB_USERNAME=your_postgres_user
# DB_PASSWORD=your_postgres_password

# Run migrations and seeding
php artisan migrate:fresh --seed
```

Expected output at end:
```
Database seeding completed successfully.
✅ 11 users created
✅ 3 departments created  
✅ 12 products created
```

### Step 2: Start Development Server (1 minute)

```bash
php artisan serve
```

Output:
```
Laravel development server started: http://127.0.0.1:8000
```

### Step 3: Access the Application (30 seconds)

Open in browser: **http://localhost:8000**

### Step 4: Login with Test Account (30 seconds)

```
Email: teacher.cs1@campus.test
Password: password
```

---

## 📊 Available Test Accounts

### By Department

**Computer Science Department**
- Teacher: `teacher.cs1@campus.test` / `password`
- HOD: `hod.cs@campus.test` / `password`

**Finance Department**
- Teacher: `teacher.finance1@campus.test` / `password`
- HOD: `hod.finance@campus.test` / `password`

### By Role

**Admin Access**
- Admin: `admin@campus.test` / `password`

**Institution-Wide Roles**
- Principal: `principal@campus.test` / `password`
- Trust Head: `trusthead@campus.test` / `password`

**Provider (Supply Management)**
- Provider: `provider@campus.test` / `password`

---

## 🎯 First Steps in Application

### If You're a Teacher
1. Login as `teacher.cs1@campus.test`
2. Go to Dashboard
3. Click "Create New Request"
4. Select products and quantities
5. Submit request
6. Wait for HOD approval

### If You're an HOD
1. Login as `hod.cs@campus.test`
2. Go to Dashboard (see pending requests)
3. Click on pending request
4. Review and approve/reject
5. Click "Approve Request"
6. Request moves to Principal

### If You're an Admin
1. Login as `admin@campus.test`
2. Go to Dashboard
3. View system statistics
4. Access Admin Panel for user/department/product management
5. Approve final stage requests for provider

### If You're a Provider
1. Login as `provider@campus.test`
2. Go to Dashboard
3. See requests sent for supply
4. Mark items as supplied
5. Stock quantities automatically updated

---

## 🔍 Verify Installation

### Check All Systems

```bash
# Run Laravel tests
php artisan test

# List all routes
php artisan route:list | head -20

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo()
# Should return PDOConnection object (no error)
```

### Verify Middleware

Test that authorization is working:
1. Login as `teacher.cs1@campus.test`
2. Try to access `/admin/users` in browser
3. Should get **403 Forbidden** page (this is correct!)
4. Check `storage/logs/laravel.log` for authorization log entry

---

## 🌐 Application Structure

### Main Pages

**Dashboards** (Role-specific)
- `/dashboard` - Your role's dashboard with statistics
- `/admin/dashboard` - Admin system dashboard

**Requests**
- `/requests` - List all requests visible to you
- `/requests/create` - Create new request (Teachers/HOD/Admin)
- `/requests/{id}` - View request details
- `/requests/{id}/edit` - Edit pending requests

**Approvals**
- `/approvals/{id}` - Approval form (HOD/Principal/TrustHead/Admin)

**Admin Panel** (Admin Only)
- `/admin/users` - Manage users
- `/admin/departments` - Manage departments
- `/admin/products` - Manage products inventory

---

## 🧪 Test the Approval Workflow

### Complete Flow (15 minutes)

1. **Teacher Creates Request**
   ```
   Login as: teacher.cs1@campus.test
   Go to: /requests/create
   Add items: Printer Paper (5 reams), Pens (10 boxes)
   Submit
   Status: pending
   ```

2. **HOD Approves**
   ```
   Login as: hod.cs@campus.test
   Go to: /dashboard
   Click pending request
   Click "Approve"
   Status: hod_approved
   ```

3. **Principal Approves**
   ```
   Login as: principal@campus.test
   Go to: /dashboard
   Click HOD-approved request
   Click "Approve"
   Status: principal_approved
   ```

4. **Trust Head Approves**
   ```
   Login as: trusthead@campus.test
   Go to: /dashboard
   Click principal-approved request
   Click "Approve"
   Status: trust_approved
   ```

5. **Admin Sends to Provider**
   ```
   Login as: admin@campus.test
   Go to: /dashboard
   Click request
   Click "Approve & Send to Provider"
   Status: sent_to_provider
   ```

6. **Provider Supplies**
   ```
   Login as: provider@campus.test
   Go to: /requests
   Find request marked "sent_to_provider"
   Click "Mark as Supplied"
   Status: completed
   Stock quantities reduced
   ```

---

## 📋 Available Features

✅ **User Management**
- 6 roles with different permissions
- Department-based access control
- Role-specific dashboards

✅ **Request Management**
- Create stationary requests
- Track request status across workflow
- View request history
- Edit/delete pending requests

✅ **Approval Workflow**
- 4-level approval process
- Role-based authorization
- Request rejection at any stage
- Approval timeline tracking

✅ **Product Management**
- Track product inventory
- Update stock quantities
- View product details

✅ **Department Management**
- Create/update departments
- Assign users to departments
- Department-scoped workflows

✅ **Admin Features**
- User management
- Department management
- Product management
- System statistics dashboard

✅ **Security**
- 5-layer middleware protection
- Authorization logging
- Error tracking
- Audit trail of approvals

---

## 🐛 Common Issues & Solutions

### Issue: Database connection error
```
Solution: Check .env file has correct PostgreSQL credentials
Run: php artisan config:cache
Verify: psql -U your_user -l (PostgreSQL is running)
```

### Issue: "No such file or directory" error
```
Solution: Make sure you're in project directory
Run: cd /home/suraj/Documents/CampusStoreManagementSystem
```

### Issue: Middleware giving 403 on every page
```
Solution: Middleware aliases might not be registered
Run: php artisan route:list (check middleware column)
Check: bootstrap/app.php has all 5 middleware aliases
```

### Issue: Migrations fail with "column does not exist"
```
Solution: Database might be partially migrated
Run: php artisan migrate:fresh --seed (resets everything)
```

### Issue: "Class not found" error
```
Solution: Autoloader cache might be stale
Run: composer dump-autoload
Then: php artisan serve
```

---

## 📚 Documentation Reference

For more detailed information:

| Document | Purpose |
|----------|---------|
| [README.md](README.md) | Project overview |
| [SETUP.md](SETUP.md) | Detailed installation |
| [MIDDLEWARE_TESTING_GUIDE.md](MIDDLEWARE_TESTING_GUIDE.md) | Test scenarios |
| [APPROVAL_WORKFLOW_GUIDE.md](APPROVAL_WORKFLOW_GUIDE.md) | Architecture details |
| [WORKFLOW_QUICK_START.md](WORKFLOW_QUICK_START.md) | Code examples |

---

## 🚀 Performance Optimization Tips

For production:

```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Use production queue
php artisan queue:listen

# Setup proper logging
# Set LOG_CHANNEL=stack in .env
```

---

## 🎓 Architecture Overview

```
User Login (Fortify)
        ↓
Middleware Layer (5 checks)
        ↓
Controller (Business logic)
        ↓
Service Layer (Workflow logic)
        ↓
Database (PostgreSQL with constraints)
        ↓
Response (Blade templates + JSON)
```

---

## 📞 Need Help?

Check these in order:
1. **Error message** → Check error on screen
2. **Browser console** → F12 → Console tab (JavaScript errors)
3. **Laravel logs** → `storage/logs/laravel.log`
4. **Database** → Connect to see actual data
5. **Documentation** → Read relevant .md files

---

## ✅ Verification Checklist

After setup, verify:

- [ ] Database migrations completed without errors
- [ ] Artisan serve starts without errors
- [ ] Can login with test account
- [ ] Dashboard loads for your role
- [ ] Can create a request (if teacher/hod/admin)
- [ ] Can see requests in list
- [ ] Middleware blocks unauthorized access (403 error)
- [ ] Logs show authorization attempts in `storage/logs/laravel.log`

---

**System Status**: ✅ Ready to Use

**Current Version**: 1.0  
**Framework**: Laravel 10  
**Database**: PostgreSQL  
**Status**: Production Ready (Testing Phase)

Get started now: `php artisan serve` then visit http://localhost:8000
