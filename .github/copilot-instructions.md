# Copilot Instructions for Login System (SaaS Multi-Tenant Platform)

## Project Overview

This is a **Laravel 10 SaaS platform** with organization-based multi-tenancy. The system manages employees, documents, tool checkout/return, and inventory requests across multiple organizations (tenants).

**Tech Stack:** Laravel 10, Blade templating, Tailwind CSS, Vite, SQLite/MySQL

---

## Architecture & Data Isolation

### Multi-Tenancy Pattern
- **Tenant Model:** Each `Organization` is a separate tenant with isolated data
- **Data Isolation:** All tables have `org_id` foreign key; users belong to exactly one org via `User.org_id`
- **Query Scoping:** Every model implements `scopeForOrganization($query, $orgId)` to prevent cross-tenant data leaks
- **Key Models:** `Organization`, `User`, `Document`, `Tool`, `ToolCheckout`, `InventoryRequest`

### Middleware Enforcement
- `OrganizationMiddleware` - Automatically injects org context; available in `request()`, views
- `AdminMiddleware` / `EmployeeMiddleware` / `SuperAdminMiddleware` - Role-based access control
- `EnsureUserIsActive` - Checks user status (active/disabled) and logs out disabled users

**Critical Pattern:** Always filter queries with `Model::forOrganization($orgId)` or call in authenticated context where `Auth::user()->org_id` is available.

---

## Role Hierarchy & Authorization

| Role | Access | Location |
|------|--------|----------|
| **Employee** | Employee routes only (dashboard, documents, tools, inventory requests) | `/` (employee pages) |
| **Admin** | Admin dashboard + all employee features + user/document/tool management | `/admin/*` |
| **Super Admin** | Full system access: organizations, all admins, all users | `/super-admin/*` |

**Route Groups in `routes/web.php`:**
```php
Route::middleware(['auth', 'employee', 'organization'])->group() // Employee
Route::middleware(['auth', 'admin', 'organization'])->prefix('admin')->group() // Admin
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->group() // Super Admin
```

---

## Feature Modules (Phases)

### Phase 1: Authentication ✅
- Login, registration, password reset via `Auth\LoginController`, `Auth\RegisterController`
- Password hashing with Laravel's default hasher

### Phase 2: Employee Documents
- Upload, review, sign documents
- Key files: `DocumentController`, `Document` model, `documents/index` view
- Status workflow: `pending_review` → `approved`/`rejected`
- Signature capture in browser

### Phase 3: Tool Check-In/Check-Out
- Browse available tools, checkout/return items
- Key files: `ToolController`, `Tool` + `ToolCheckout` models
- Return due date validation; due-soon alerts on dashboard
- Tool condition tracking (good/fair/needs_repair)

### Phase 4: Inventory Requests
- Employees submit requests; admins approve/deny
- Multi-item requests with line items
- Event-driven notifications for status changes
- Key files: `InventoryRequestController`, `InventoryRequest` + `InventoryRequestItem` models

---

## Database Schema Essentials

### Core Tables
- `users` - Has `org_id`, `role`, `status` (active/disabled), `is_super_admin`
- `organizations` - Tenant container; slug-based unique identifier
- `documents` - User-uploaded files; status: pending_review/approved/rejected
- `tools` - Inventory of tools per org
- `tool_checkouts` - Loan records linking user→tool with due_date, condition notes
- `inventory_requests` - Multi-step approval workflow with items table
- `inventory_request_items` - Line items within inventory requests

**All user-facing tables include:** `org_id`, `created_at`, `updated_at`  
**Key indices:** `(org_id, user_id)`, `(org_id, status)` for performance

---

## Critical Conventions & Patterns

### 1. Model Scoping (Mandatory)
Every model query **must** filter by `org_id`. Use:
```php
// ❌ WRONG - Cross-tenant leak
Document::where('user_id', $userId)->get();

// ✅ CORRECT - Org-scoped
Document::forOrganization($orgId)->where('user_id', $userId)->get();
```

### 2. Getting Organization ID
```php
// In authenticated context
$orgId = Auth::user()->org_id; // Also available in request()->attributes->get('org_id')

// From view (middleware injects globally)
{{ $org_id }} or {{ $organization->id }}
```

### 3. Relationships in Models
```php
// Every model has org relationship
public function organization() { return $this->belongsTo(Organization::class, 'org_id'); }

// Combine with other relationships
Document::forOrganization($orgId)->with('user', 'organization')->paginate();
```

### 4. Service Pattern
Use `OrganizationService` for org-related helpers:
```php
OrganizationService::current() // Get current org from Auth::user()
OrganizationService::currentId() // Get current org ID
OrganizationService::canAccess($organization) // Permission check
```

### 5. View Data Passing
Controllers compact variables explicitly; views use:
```blade
{{ $variable ?? 'default' }}  <!-- Safe null-check -->
@auth @endauth  <!-- Authentication blocks -->
@if(Auth::user()->role === 'admin') @endif  <!-- Role checks -->
```

---

## Event-Driven Notifications

**Notification System Architecture:**
- **Storage:** `AppNotification` model stores notifications in `notifications` table with `user_id`, `org_id`, `type`, `title`, `message`, `link_url`
- **UI Locations:**
  1. **Bell Icon (Primary):** Top navbar dropdown showing last 10 notifications with unread badge
  2. **Notifications Page:** Full list at `/notifications` with filters (status: all/unread/read, type: documents/tools/inventory)
  3. **Sidebar Menu:** "Notifications" menu item in employee/admin sidebars

**Notification Types & Triggers:**

### Phase 2 (Documents)
- `documents.assigned` - Employee receives new document
- `documents.requires_signature` - Employee needs to sign document

### Phase 3 (Tools)
- `tools.checkout_confirmed` - Employee confirms checkout
- `tools.due_soon` - Reminder 24h before due (future scheduler job)
- `tools.return_confirmed` - Return confirmation

### Phase 4 (Inventory Requests)
- `inventory_requests.approved` - Request approved by admin
- `inventory_requests.denied` - Request denied by admin
- `inventory_requests.fulfilled` - Ready for pickup

**Event Registration (EventServiceProvider.php):**
```php
protected $listen = [
    InventoryRequestApproved::class => [NotifyInventoryRequestApproved::class],
    InventoryRequestDenied::class => [NotifyInventoryRequestDenied::class],
    InventoryRequestFulfilled::class => [NotifyInventoryRequestFulfilled::class],
];
```

**Creating Notifications in Code:**
```php
// Use AppNotification::createNotification() in event listeners
AppNotification::createNotification(
    user: $user,
    type: 'documents.assigned',
    title: 'New Document',
    message: 'Document "Contract" assigned to you',
    linkUrl: route('documents.show', $document),
    data: ['document_id' => $document->id]
);
```

**Query Examples:**
```php
// Unread for current user
AppNotification::forUser(Auth::id())->unread()->get();

// By type category (documents.*, tools.*, inventory_requests.*)
AppNotification::byTypeCategory('documents')->recent(30)->get();

// Mark as read
$notification->markAsRead(); // or markAsUnread()
```

**Frontend Integration:**
- Bell component at `resources/views/components/notification-bell.blade.php`
- Auto-refreshes every 30 seconds
- Notifications page at `resources/views/notifications/index.blade.php` with pagination & filters

---

## Build & Development Workflow

### Setup
```powershell
composer install
npm install
php artisan migrate --seed  # Run migrations + seeders
php artisan serve           # Start Laravel dev server (localhost:8000)
npm run dev                 # Watch Tailwind/Vite for CSS/JS changes
```

### Testing
```powershell
php artisan test                    # Run all tests
php artisan test --filter=TestName  # Specific test
./vendor/bin/phpunit
```

### Database
```powershell
php artisan migrate          # Apply migrations
php artisan migrate:rollback # Undo last batch
php artisan migrate:reset    # Undo all, then re-apply
php artisan db:seed          # Run seeders
```

### Cache/Optimization
```powershell
php artisan cache:clear
php artisan view:clear
php artisan route:cache     # Production only
```

---

## Common Workflows

### Adding a New Feature
1. Create **Model** with `org_id` field + `scopeForOrganization()`
2. Create **Migration** with foreign keys and indices
3. Create **Controller** with org-scoped queries
4. Add **Routes** in appropriate middleware group (`employee`, `admin`, `super-admin`)
5. Create **Views** (Blade templates in `resources/views/{role}/{feature}/`)
6. If async: Add **Event** + **Notification** + register in `EventServiceProvider`

### Querying Across Relationships
```php
// Single user's documents
Document::forOrganization($orgId)->where('user_id', $userId)->get();

// Admin viewing all org documents
Document::forOrganization($orgId)->with('user')->paginate();

// Tools checked out but not returned
ToolCheckout::forOrganization($orgId)->where('status', 'checked_out')
    ->whereDate('return_due_date', '<=', now()->addDays(3))->get();
```

### Authorization in Controllers
```php
$document = Document::forOrganization($orgId)->findOrFail($id);
if ($document->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
    abort(403); // Unauthorized
}
```

---

## File Structure Reference

```
app/
  ├─ Models/          → Data models with org scopes
  ├─ Http/
  │  ├─ Controllers/  → Employee, Admin, SuperAdmin subdirs
  │  └─ Middleware/   → Role & org enforcement
  ├─ Events/          → Event classes (inventory, document approvals)
  ├─ Services/        → Helper classes (OrganizationService)
routes/
  └─ web.php         → All route groups with middleware
resources/views/
  ├─ dashboard/      → Employee dashboard
  ├─ employee/       → Documents, tools, inventory requests
  ├─ admin/          → Admin interface
  ├─ layouts/        → Shared templates & sidebars
database/
  ├─ migrations/     → Schema (check org_id pattern)
  └─ seeders/        → Seeder data for testing
```

---

## Debugging Tips

- **Org Scope Violations:** Search for direct model queries without `.forOrganization()`
- **View Scope Issues:** Check if middleware is applied to route
- **Notification Not Sending:** Verify `EventServiceProvider` has listener registered; check `notifications` table (database channel)
- **Middleware Redirects:** Check `AdminMiddleware`, `EmployeeMiddleware` — they may reject/logout on disabled users
- **Dashboard Stats Stale:** Stats are computed in `DashboardController` on each request; no caching

---

## Key Files to Review When Starting

1. **`app/Models/User.php`** - Auth model with org relationship
2. **`routes/web.php`** - Route structure & middleware groups
3. **`app/Http/Middleware/OrganizationMiddleware.php`** - Org context injection
4. **`app/Models/Document.php`** (or any model) - Scope pattern example
5. **`resources/views/dashboard/index.blade.php`** - Main employee dashboard
6. **`SAAS_IMPLEMENTATION.md`** - Architecture rationale
7. **`DOCUMENTS_HUB_QUICK_REFERENCE.md`** - Feature specification docs

---

## Quick Dos & Don'ts

| ✅ Do | ❌ Don't |
|------|---------|
| Filter all queries by `org_id` | Query without org scope |
| Use `Auth::user()->org_id` in controllers | Hardcode org IDs in tests |
| Register listeners in `EventServiceProvider` | Fire events without listeners |
| Test with different orgs in seeder data | Assume single-org behavior |
| Validate file uploads + mime types | Trust user input for file paths |
| Use Blade form directives (`@csrf`, `@method`) | Build forms manually |

---

