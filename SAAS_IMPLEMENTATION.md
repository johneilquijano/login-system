# SaaS Multi-Tenancy Implementation Guide

**Date:** December 12, 2025  
**Status:** In Progress  
**Approach:** Organization-based multi-tenancy with isolated data per company

---

## Architecture Overview

This login system is being converted to a **SaaS platform** where:
- Each **Organization** is a separate tenant
- Users belong to exactly one organization (`org_id` foreign key)
- All data (documents, tools, inventory) is isolated by organization
- Data isolation is enforced at both database and application levels

---

## Completed ✅

### 1. Database Schema
- ✅ Organizations table (slug, name, status, etc.)
- ✅ Users table with `org_id` foreign key
- ✅ Documents table with `org_id` + audit fields
- ✅ ToolCheckouts table with `org_id` + approval workflow
- ✅ InventoryRequests table with `org_id` + multi-step approval

**Key Design Decisions:**
- Composite unique indices on `(org_id, email)` for users
- Foreign key cascading to prevent orphaned records
- Status enums for workflow tracking (draft → submitted → approved)
- Timestamps for audit trails

### 2. Models & Relationships
- ✅ Organization model with hasMany users/admins/employees
- ✅ User model with belongsTo organization
- ✅ Document, ToolCheckout, InventoryRequest models with org scoping
- ✅ Query scopes: `forOrganization()`, `pending()`, `active()`

### 3. Middleware & Services
- ✅ OrganizationMiddleware: Automatically injects org context
- ✅ OrganizationService: Helper methods for org operations

---

## In Progress 🔄

### Step 3: Update All Controllers

**Controllers that need org filtering:**

```
app/Http/Controllers/
  ├── DashboardController         ← Filter by org_id
  ├── DocumentController          ← Filter by org_id
  ├── ToolCheckoutController      ← Filter by org_id
  ├── InventoryRequestController  ← Filter by org_id
  └── Admin/
      ├── DashboardController     ← Show org stats
      └── UserController          ← Only show org users
```

**Pattern for all controllers:**

```php
// WRONG (Shows ALL users in database):
$users = User::all();

// CORRECT (Shows only this org's users):
use App\Services\OrganizationService;

$users = User::forOrganization(OrganizationService::currentId())->get();
// OR shorter:
$users = User::where('org_id', auth()->user()->org_id)->get();
```

### Step 4: URL Routing Strategy

**Current setup (single tenant):**
```
GET /dashboard
GET /admin/users
POST /documents
```

**New SaaS setup (multi-tenant with subdomains):**
```
company1.app.com/dashboard
company2.app.com/dashboard
app.com/organizations/company1/dashboard  (alternative)
```

**Implementation approach:**

```php
// Option 1: Subdomain-based (recommended for SaaS)
Route::domain('{subdomain}.app.local')->group(function () {
    Route::middleware('organization')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });
});

// Option 2: URL path-based
Route::prefix('/{org:slug}')->middleware('organization')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

---

## Not Started Yet ❌

### Step 5: Organization Management (Super Admin Panel)
- Create organization CRUD operations
- Ability to create/enable/disable companies
- Super admin dashboard showing all organizations
- Organization settings & billing integration

### Step 6: Mobile & Real-time Features
- Responsive mobile design (Tailwind mobile-first already in place)
- Document signature capture on mobile (SignaturePad or similar)
- Push notifications & SMS integration
- WebSocket support for real-time updates

### Step 7: Testing & Verification
- Unit tests for org isolation
- Integration tests for multi-tenant scenarios
- Security audit for data leakage

---

## Security Considerations ⚠️

1. **Always filter by organization:**
   ```php
   // ALWAYS do this:
   $documents = Document::where('org_id', auth()->user()->org_id)->get();
   ```

2. **Middleware enforcement:**
   - OrganizationMiddleware ensures `auth()->user()->org_id` is always available
   - Add to all protected routes

3. **Foreign key validation:**
   - When updating/deleting, verify resource belongs to user's organization
   - Example: Don't allow user from Org A to delete document from Org B

4. **Authorization patterns:**
   ```php
   // Check org ownership before allowing operation
   $document = Document::find($id);
   if ($document->org_id !== auth()->user()->org_id) {
       abort(403, 'Unauthorized');
   }
   ```

---

## Suggested Implementation Order

1. ✅ Create/update models with relationships
2. ✅ Create migrations with org_id
3. 🔄 **UPDATE CONTROLLERS** (next priority)
4. Implement organization scoping in queries
5. Add subdomain routing
6. Create super-admin organization management
7. Add mobile UI enhancements
8. Integration testing

---

## File Locations Reference

**Core Files:**
- Migrations: `database/migrations/migrations/`
- Models: `app/Models/`
- Controllers: `app/Http/Controllers/`
- Middleware: `app/Http/Middleware/`
- Services: `app/Services/`

**Key Files Modified:**
- `app/Models/User.php` - Added organization() relationship
- `app/Models/Organization.php` - Added user relationship methods
- `app/Http/Middleware/OrganizationMiddleware.php` - NEW
- `app/Services/OrganizationService.php` - NEW
- `database/migrations/migrations/2024_12_12_000005_create_documents_table.php` - NEW
- `database/migrations/migrations/2024_12_12_000006_create_tool_checkouts_table.php` - NEW
- `database/migrations/migrations/2024_12_12_000007_create_inventory_requests_table.php` - NEW

---

## Database Diagram

```
organizations
├── id (PK)
├── name
├── slug
├── status
└── ...metadata

users
├── id (PK)
├── org_id (FK) ──→ organizations.id
├── email (unique per org)
├── role (admin|employee)
└── ...

documents
├── id (PK)
├── org_id (FK) ──→ organizations.id
├── user_id (FK) ──→ users.id
├── status
└── ...

tool_checkouts
├── id (PK)
├── org_id (FK) ──→ organizations.id
├── user_id (FK) ──→ users.id
└── ...

inventory_requests
├── id (PK)
├── org_id (FK) ──→ organizations.id
├── user_id (FK) ──→ users.id
└── ...
```

---

## Next Steps

1. **Update all controllers** to filter queries by organization
2. **Run migrations** to create new tables
3. **Test data isolation** with multiple organizations
4. **Implement subdomain routing** for cleaner URLs
5. **Add mobile enhancements** for document signing
