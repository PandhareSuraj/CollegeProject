# Campus Store Management System - Features & Use Cases

## System Overview

A comprehensive Laravel 10 application designed to manage stationary product requests within a college campus with a sophisticated multi-level approval workflow.

---

## Core Features

### 1. Multi-Role Authentication & Authorization

#### Available Roles
- **Admin**: System administrator with full control
- **Teacher**: Department member who creates requests
- **HOD**: Department Head who gives first approval
- **Principal**: Institution Head who gives second approval
- **Trust Head**: Trust authority who gives final approval
- **Provider**: Supplier who executes the request

#### Security Features
- Password hashing with bcrypt
- Role-based middleware protection
- CSRF token protection on all forms
- Mass assignment protection
- Secure session management

---

### 2. Request Management System

#### Create Request (Teacher/Admin)
- **Multi-item additions**: Add multiple products in single request
- **Dynamic calculations**: Total amount calculated automatically
- **Flexible quantities**: Adjust quantities before submission
- **Item removal**: Remove items before submission
- **Draft support**: Save as draft functionality (future)

#### Request Properties
- Request ID (Auto-generated)
- Department association
- Requestor information
- Product items with quantities and prices
- Total amount calculation
- Timestamps (created, updated)
- Status tracking

#### Request Lifecycle States
```
pending
    ↓
hod_approved (or rejected)
    ↓
principal_approved (or rejected)
    ↓
trust_approved (or rejected)
    ↓
sent_to_provider (or rejected)
    ↓
completed
```

#### Request Operations
- **Create**: Teachers create new requests
- **Read**: View request details and history
- **Update**: Edit pending requests only
- **Delete**: Remove pending requests
- **View Timeline**: See approval history with remarks

---

### 3. Approval Workflow System

#### Approval Process

**Step 1: HOD Approval (Department Level)**
- HOD sees pending requests from their department
- Reviews items and total amount
- Can approve or reject with remarks
- Approval moves request to principal

**Step 2: Principal Approval (Institution Level)**
- Principal sees HOD-approved requests
- Reviews department and details
- Can approve or reject
- Approval moves to trust head

**Step 3: Trust Head Approval (Trust Level)**
- Trust head sees principal-approved requests
- Final authorization check
- Can approve or reject
- Approval sends to provider

**Step 4: Admin/Provider Actions**
- Admin can review all approved requests
- Provider receives approved requests
- Provider marks items as supplied
- Stock automatically reduced

#### Approval Features
- Remarks/comments on each approval
- Approval timestamp tracking
- Approval by user record
- Rejection with reason
- Approval timeline display

---

### 4. Dashboard System

#### Admin Dashboard
- System-wide statistics
- Total requests overview
- Pending approvals count
- Approved/completed counts
- Rejected count
- Total user count
- Total system amount
- Quick management links

#### Teacher Dashboard
- Personal request statistics
- Recent requests list
- Status at a glance
- Create new request button
- View request details in one click

#### HOD Dashboard
- Department-specific view
- Pending approvals for department
- Department member count
- Department request statistics
- Approved vs rejected breakdown
- Quick approval access

#### Principal Dashboard
- Institution-wide pending requests (HOD-approved)
- Approval count statistics
- Completed requests tracking
- Global statistics
- Batch review capabilities

#### Trust Head Dashboard
- Trust-level approvals pending (principal-approved)
- Trust-wide statistics
- Final authorization view
- Completed tracking

#### Provider Dashboard
- Requests ready for supply
- Supply list view
- Mark as supplied functionality
- Completion tracking
- Stock reduction log

---

### 5. Product Management

#### Product Attributes
- Product Name (unique)
- Description
- Price (in currency)
- Stock Quantity
- Creation timestamp
- Update timestamp

#### Admin Functions
- Add new products
- Edit product details
- Update prices
- Track stock levels
- Delete unused products
- View product usage

#### Stock Management
- Initial stock entry
- Automatic reduction on supply
- Stock status indicators
- Low stock alerts (future)
- Stock history (future)

---

### 6. Department Management

#### Department Structure
- Department Name (unique)
- Description
- User assignments
- Request tracking
- HOD assignment

#### Department Operations
- Create new departments
- Edit department info
- Assign users to departments
- View department statistics
- Department request history
- Department member list

#### Department Statistics
- Total users in department
- Total requests from department
- Pending requests
- Completed requests
- Total spent

---

### 7. User Management

#### User Information
- Name
- Email (unique)
- Password (hashed)
- Role assignment
- Department assignment
- Account creation date
- Authentication status

#### Admin User Functions
- Create new users with roles
- Assign departments
- Edit user information
- Reset passwords
- Deactivate/delete users
- View user statistics

#### User Role Assignment
- Single role per user
- Role-specific permissions
- Department-based restrictions
- Dynamic access control

---

### 8. Reporting & Analytics

#### Statistics Available
- Total requests by status
- Requests by department
- Average processing time (future)
- Stock movement
- User activity
- Approval metrics

#### Views Available
- Admin overview
- Department breakdowns
- User statistics
- Product performance
- Approval efficiency (future)

---

## Use Case Scenarios

### Scenario 1: Routine Stationary Request

**Actors**: Teacher, HOD, Principal, Trust Head, Provider

1. **Day 1 - Teacher Creates Request**
   - Teacher logs in
   - Goes to "Create Request"
   - Adds 5 notebooks (price: 25, qty: 10) = 250
   - Adds 2 pen packs (price: 50, qty: 2) = 100
   - Total: ₹350
   - Submits request
   - Status: `pending`

2. **Day 1 - HOD Reviews**
   - HOD sees pending request from department
   - Reviews items and amounts
   - Approves "Looking good, proceed"
   - Status: `hod_approved`

3. **Day 2 - Principal Reviews**
   - Principal sees HOD-approved request
   - Checks budget/appropriateness
   - Approves "Authorized"
   - Status: `principal_approved`

4. **Day 2 - Trust Head Reviews**
   - Trust head sees principal-approved request
   - Final check
   - Approves "Proceed to supply"
   - Status: `trust_approved`

5. **Day 3 - Admin Forwards to Provider**
   - Admin reviews approved requests
   - Sends request to provider
   - Status: `sent_to_provider`

6. **Day 5 - Provider Supplies**
   - Provider receives items
   - Verifies quantities and items
   - Marks request as "Supplied"
   - Stock reduced: Notebooks -10, Pens -2
   - Status: `completed`

7. **Day 5 - Department Receives**
   - Teacher receives items in department
   - Request complete in system

---

### Scenario 2: Request Rejection

**Actors**: Teacher, HOD

1. **Teacher submits excessive quantity request**
   - Request ₹5000 for 200 notebooks
   - Status: `pending`

2. **HOD Reviews and Rejects**
   - HOD feels quantity is too high
   - Rejects with remark: "Excessive quantity. Please resubmit with justified amount"
   - Status: `rejected`

3. **Teacher Reviews Rejection**
   - Sees HOD's remarks
   - Can create new request with adjusted quantity
   - New request: 50 notebooks (₹1250)
   - Submits again

---

### Scenario 3: Multi-Department Requests

**Scenario**: Multiple departments creating requests simultaneously

1. **CS Department**: 5 pending requests
2. **ECE Department**: 3 pending requests
3. **ME Department**: 2 pending requests

**HOD Actions**:
- Each HOD sees only their department's requests
- Each HOD independently approves/rejects
- No cross-department visibility

**Principal Actions**:
- Sees all 10 HOD-approved requests
- Reviews collectively
- Approves all or selective

---

### Scenario 4: Large Order Split

**Scenario**: Request for 1000 notebooks is too large

1. **Teacher submits 1000 notebooks (₹25,000)**
   - Too large for single approval
   - HOD rejects: "Split into multiple orders"

2. **Teacher creates 4 requests**
   - Request 1: 250 notebooks
   - Request 2: 250 notebooks
   - Request 3: 250 notebooks
   - Request 4: 250 notebooks
   - Each goes through approval separately

---

## Advanced Features

### Approval Tracking
- Full timeline of who approved when
- Remarks preserved at each level
- Rejection reasons tracked
- Re-submission capability

### Role-Based Visibility
- Teachers see only their requests
- HOD sees department requests
- Principal sees all after HOD approval
- Trust head sees all after principal approval
- Provider sees final approved requests

### Status Indicators
- Color-coded status badges
- Visual workflow indicators
- Real-time status updates
- Request timeline display

### Security measures
- Authorization checks on every action
- Role verification middleware
- CSRF protection
- Input validation
- SQL injection prevention

---

## System Metrics & Counters

### Admin Dashboard Shows
- Total Requests: **X**
- Pending: **X** (Awaiting approval)
- Approved: **X** (All approval levels)
- Completed: **X** (Supplied by provider)
- Rejected: **X** (Rejected at any level)
- Total Users: **X**
- Total Amount: **₹X**

### Department Dashboard Shows
- Department Total: **X** requests
- Pending Approvals: **X**
- Approved: **X**
- Completed: **X**

### Requesting User Dashboard Shows
- My Requests: **X** total
- Pending: **X** (Waiting for approval)
- Approved: **X** (Passed all approvals)
- Completed: **X** (Delivered)

---

## Performance Features

### Optimization
- Eager loading of relationships (prevents N+1)
- Pagination (15 items per page)
- Indexed database columns
- Efficient queries with Eloquent ORM
- Session-based caching

### Scalability
- Handles multiple parallel requests
- Department segregation prevents overload
- User-specific filtering
- Pagination for large datasets

---

## Future Enhancement Ideas

1. **Email Notifications**
   - Request status change notifications
   - Approval pending alerts
   - Request rejected notifications

2. **Request Templates**
   - Save common request patterns
   - Quick reorder functionality
   - Recurring requests

3. **Budget Management**
   - Department budgets
   - Budget tracking & alerts
   - Spending limits enforcement

4. **Advanced Filtering**
   - Date range filters
   - Amount range filters
   - Request type filters
   - Custom saved filters

5. **Export Functionality**
   - PDF report generation
   - Excel export
   - Scheduled reports

6. **Request Cancellation**
   - Cancel approved requests
   - Revert supplied items
   - Reason documentation

7. **Mobile Application**
   - Mobile-responsive design
   - Native app
   - Push notifications

8. **Supplier Management**
   - Multiple suppliers
   - Supplier ratings
   - Delivery tracking

9. **Item Analytics**
   - Most requested items
   - Spending by category
   - Trend analysis

10. **Two-Factor Authentication**
    - Already integrated with Laravel Fortify
    - SMS/Email OTP
    - Authenticator app support

---

## Testing Credentials

### Quick Test Users

After running `php artisan db:seed`:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@campus.test | password |
| Principal | principal@campus.test | password |
| Trust Head | trusthead@campus.test | password |
| HOD (CS) | hod.cs@campus.test | password |
| Teacher (CS) | teacher.cs1@campus.test | password |
| Provider | provider@campus.test | password |

---

## System Requirements

- **PHP**: 8.2+ with extensions (OpenSSL, PDO, Mbstring)
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Web Server**: Apache 2.4+ or Nginx 1.10+
- **Node.js**: 16+ (for asset compilation)
- **Memory**: Minimum 512MB RAM
- **Storage**: Minimum 100MB free disk space

---

## Troubleshooting Common Issues

### Issue: Can't see requests in HOD dashboard
- **Solution**: Check if user has HOD role and department assignment

### Issue: Request doesn't move to next approval level
- **Solution**: Verify previous approver has approved first

### Issue: Duplicate approval records
- **Solution**: Check form submission wasn't double-clicked

### Issue: Stock didn't reduce after supply
- **Solution**: Verify request was marked as "completed" not just "supplied"

---

**Last Updated**: February 23, 2026
**Version**: 1.0
