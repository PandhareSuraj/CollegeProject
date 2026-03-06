# Campus Store Management System - Installation Verification Checklist

Use this checklist to verify your installation is complete and working correctly.

---

## Pre-Installation Requirements

- [ ] PHP 8.2 or higher installed
- [ ] Composer installed
- [ ] MySQL/MariaDB installed and running
- [ ] Node.js 16+ installed
- [ ] npm or yarn installed
- [ ] Git installed (optional)

---

## Installation Steps Checklist

### 1. Project Setup
- [ ] Navigate to project directory: `/home/suraj/Documents/CampusStoreManagementSystem`
- [ ] Verify `.env.example` exists
- [ ] Verify `.gitignore` exists

### 2. Dependencies Installation
```bash
composer install
npm install
```
- [ ] No errors during composer install
- [ ] No errors during npm install
- [ ] `vendor/` directory created
- [ ] `node_modules/` directory created

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```
- [ ] `.env` file created from `.env.example`
- [ ] `APP_KEY` set in `.env`
- [ ] Database credentials configured in `.env`

### 4. Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE campus_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```
- [ ] Database `campus_store` created successfully
- [ ] MySQL connection working

### 5. Migrations & Seeding
```bash
php artisan migrate
php artisan db:seed
```
- [ ] All migrations completed without errors
- [ ] Database tables created (6 tables)
- [ ] Sample data seeded (users, departments, products)

### 6. Asset Compilation
```bash
npm run dev
```
- [ ] Assets compiled successfully
- [ ] `public/build/` directory created
- [ ] No compilation errors

### 7. Server Start
```bash
php artisan serve
```
- [ ] Server started successfully
- [ ] Available at `http://localhost:8000`
- [ ] No port conflicts

---

## File Structure Verification

### Migrations (database/migrations/)
- [ ] `2025_01_01_000000_create_departments_table.php`
- [ ] `2025_01_01_000001_create_products_table.php`
- [ ] `2025_01_02_000000_modify_users_table.php`
- [ ] `2025_01_02_000001_create_requests_table.php`
- [ ] `2025_01_02_000002_create_request_items_table.php`
- [ ] `2025_01_02_000003_create_approvals_table.php`

### Models (app/Models/)
- [ ] `Department.php`
- [ ] `Product.php`
- [ ] `StationaryRequest.php`
- [ ] `RequestItem.php`
- [ ] `Approval.php`
- [ ] `User.php` (modified)

### Controllers (app/Http/Controllers/)
- [ ] `DashboardController.php`
- [ ] `RequestController.php`
- [ ] `ApprovalController.php`
- [ ] `ProductController.php`
- [ ] `Admin/DepartmentController.php`
- [ ] `Admin/UserController.php`

### Middleware (app/Http/Middleware/)
- [ ] `CheckRole.php`

### Views (resources/views/)
**Dashboards:**
- [ ] `dashboards/admin.blade.php`
- [ ] `dashboards/teacher.blade.php`
- [ ] `dashboards/hod.blade.php`
- [ ] `dashboards/principal.blade.php`
- [ ] `dashboards/trust-head.blade.php`
- [ ] `dashboards/provider.blade.php`

**Requests:**
- [ ] `requests/index.blade.php`
- [ ] `requests/create.blade.php`
- [ ] `requests/show.blade.php`
- [ ] `requests/edit.blade.php`

**Approvals:**
- [ ] `approvals/show.blade.php`

**Admin:**
- [ ] `admin/products/index.blade.php`
- [ ] `admin/products/create.blade.php`
- [ ] `admin/products/edit.blade.php`
- [ ] `admin/products/show.blade.php`
- [ ] `admin/departments/index.blade.php`
- [ ] `admin/departments/create.blade.php`
- [ ] `admin/departments/edit.blade.php`
- [ ] `admin/departments/show.blade.php`
- [ ] `admin/users/index.blade.php`
- [ ] `admin/users/create.blade.php`
- [ ] `admin/users/edit.blade.php`
- [ ] `admin/users/show.blade.php`

**Layout:**
- [ ] `layouts/app.blade.php`

### Configuration
- [ ] `bootstrap/app.php` (middleware registered)
- [ ] `routes/web.php` (all routes defined)
- [ ] `database/seeders/DatabaseSeeder.php` (sample data seeder)

### Documentation
- [ ] `README.md`
- [ ] `SETUP.md`
- [ ] `FEATURES.md`
- [ ] `DEVELOPER_GUIDE.md`
- [ ] `PROJECT_SUMMARY.md` (this file's companion)

---

## Database Verification

### Tables Created
```bash
mysql -u root -p campus_store -e "SHOW TABLES;"
```
- [ ] `users` table exists
- [ ] `departments` table exists
- [ ] `products` table exists
- [ ] `requests` table exists
- [ ] `request_items` table exists
- [ ] `approvals` table exists

### Sample Data Inserted
```bash
mysql -u root -p campus_store -e "SELECT COUNT(*) FROM users;" 
```
- [ ] At least 10 users created
- [ ] 3 departments created
- [ ] 12 products created

---

## Application Tests

### 1. Authentication Test
- [ ] Visit `http://localhost:8000/login`
- [ ] Login page displays
- [ ] Login with `admin@campus.test` / `password`
- [ ] Redirect to dashboard successful

### 2. Admin Dashboard Test
- [ ] Admin dashboard visible
- [ ] Statistics show correctly:
  - [ ] Total Requests count
  - [ ] Pending count
  - [ ] Approved count
  - [ ] Completed count
  - [ ] Total Users count
  - [ ] Total Amount

### 3. Teacher Dashboard Test
- [ ] Login as `teacher.cs1@campus.test` / `password`
- [ ] Teacher dashboard visible
- [ ] "Create Request" button visible
- [ ] My requests displayed

### 4. Request Creation Test
- [ ] Click "Create Request"
- [ ] Form displays with product selector
- [ ] Can add items
- [ ] Total amount calculates automatically
- [ ] Can submit request
- [ ] Request created successfully

### 5. HOD Dashboard Test
- [ ] Login as `hod.cs@campus.test` / `password`
- [ ] HOD dashboard visible
- [ ] Pending approvals show
- [ ] Can view request details
- [ ] Can approve/reject with remarks

### 6. Approval Workflow Test
- [ ] HOD approves request
- [ ] Status changes to `hod_approved`
- [ ] Principal sees request in their dashboard
- [ ] Principal approves
- [ ] Principal → Trust Head chain works
- [ ] All approvals track correctly

### 7. Provider Test
- [ ] Login as `provider@campus.test` / `password`
- [ ] Provider dashboard shows pending supply
- [ ] Can mark request as supplied
- [ ] Status changes to `completed`
- [ ] Product stock reduces automatically

### 8. Admin Management Test
- [ ] Admin → Users management works
- [ ] Can create new user
- [ ] Can edit user
- [ ] Can view user details
- [ ] Admin → Products management works
- [ ] Can add products
- [ ] Can edit/delete products
- [ ] Admin → Departments works
- [ ] Can create departments
- [ ] Can view department statistics

### 9. Responsive Design Test
- [ ] Open on desktop browser
- [ ] Layout displays correctly
- [ ] Sidebar navigation works
- [ ] Tables are readable
- [ ] Resize to mobile width (via dev tools)
- [ ] Mobile layout works
- [ ] Bootstrap responsive classes working

### 10. Error Handling Test
- [ ] Try to access unauthorized URL
- [ ] 403 error displays (or redirect to login)
- [ ] Submit form with validation errors
- [ ] Error messages display correctly
- [ ] Try to delete item with confirmation
- [ ] Confirmation dialog works

---

## Performance Verification

### Load Time
- [ ] Dashboard loads in < 1 second
- [ ] Requests page loads in < 2 seconds
- [ ] Admin pages load in < 2 seconds

### Database Queries
- [ ] No N+1 query issues
- [ ] Eager loading working
  ```bash
  # In controller, verify relationships are loaded
  $requests = StationaryRequest::with(['department', 'requestedBy', 'items'])->get();
  ```

### Asset Files
- [ ] CSS loads correctly (no 404 errors)
- [ ] JavaScript loads correctly
- [ ] Icons display (Font Awesome working)
- [ ] Bootstrap styling applied

---

## Security Verification

### CSRF Protection
- [ ] All forms have `@csrf` token
- [ ] Forms with POST/PUT/DELETE have CSRF tokens

### Authentication
- [ ] Can't access `/dashboard` without login
- [ ] Session expires after inactivity (optional)
- [ ] Logout works correctly

### Authorization
- [ ] Teacher can't access admin panel
- [ ] Non-admin can't access user management
- [ ] Middleware properly blocks unauthorized access
- [ ] User can't edit other user's profile

### Password Security
- [ ] Password hashing working (bcrypt)
- [ ] Can't see passwords in database
- [ ] Password reset works (optional)

---

## Documentation Verification

- [ ] README.md is comprehensive
- [ ] SETUP.md has clear instructions
- [ ] FEATURES.md explains all features
- [ ] DEVELOPER_GUIDE.md is helpful
- [ ] PROJECT_SUMMARY.md explains what was created

---

## Troubleshooting Checklist

If something isn't working:

### Installation Issues
- [ ] Run `composer install` again
- [ ] Run `npm install` again
- [ ] Delete `vendor/` and reinstall composer
- [ ] Check PHP version: `php -v`
- [ ] Check MySQL connection

### Database Issues
- [ ] Verify .env database credentials
- [ ] Check MySQL is running
- [ ] Run `php artisan migrate` again
- [ ] Run `php artisan migrate:reset` then `php artisan migrate`
- [ ] Check error log: `storage/logs/laravel.log`

### Asset Issues
- [ ] Run `npm run dev` again
- [ ] Delete `public/build/` and rebuild
- [ ] Clear browser cache
- [ ] Disable browser extensions

### Route Issues
- [ ] Run `php artisan route:clear`
- [ ] Verify routes are registered in `routes/web.php`
- [ ] Check middleware is registered in `bootstrap/app.php`

### View Issues
- [ ] Run `php artisan view:clear`
- [ ] Check view file paths are correct
- [ ] Verify Blade syntax is correct

### Permission Issues
- [ ] Ensure `storage/` is writable: `chmod -R 755 storage`
- [ ] Ensure `bootstrap/cache` is writable
- [ ] Run as appropriate user (not root in production)

---

## Final Sign-Off

When all tests pass:
- [ ] System is ready for development
- [ ] System is ready for testing
- [ ] System is ready for deployment

### Deployment Readiness
Before deploying to production:
- [ ] Set `.env` `APP_DEBUG` to `false`
- [ ] Set `.env` `APP_ENV` to `production`
- [ ] Run `php artisan config:clear`
- [ ] Run `php artisan cache:clear`
- [ ] Run tests: `php artisan test`
- [ ] Set proper file permissions
- [ ] Backup database before deployment
- [ ] Set up monitoring/logging
- [ ] Configure SSL certificate (HTTPS)

---

## Quick Reference Commands

```bash
# Development
php artisan serve                    # Start dev server
npm run dev                          # Watch assets
php artisan tinker                   # Interactive shell

# Database
php artisan migrate                  # Run migrations
php artisan migrate:refresh          # Reset and migrate
php artisan db:seed                  # Seed sample data

# Cache Clearing
php artisan config:clear             # Clear config
php artisan cache:clear              # Clear cache
php artisan route:clear              # Clear routes
php artisan view:clear               # Clear compiled views

# Testing
php artisan test                     # Run tests

# Deployment
php artisan config:cache             # Cache config
npm run build                        # Build production assets
```

---

**Verification Date**: ___________
**Verified By**: ___________
**Status**: ☐ ALL TESTS PASSED ☐ DEPLOYMENT READY

---

See PROJECT_SUMMARY.md for complete overview of what was created.
