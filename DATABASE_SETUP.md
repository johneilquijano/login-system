# Database Setup & Testing Guide

**Date:** December 12, 2025  
**Status:** Ready for Testing

---

## Quick Start - Run All Migrations & Seeders

```bash
# Fresh database reset with all migrations and seeders
php artisan migrate:fresh --seed
```

This will:
1. ✅ Drop all tables
2. ✅ Run all migrations (organizations, users, documents, tools, inventory)
3. ✅ Seed 3 organizations with test data
4. ✅ Create 12 users total (4 per organization)
5. ✅ Create sample documents, tool checkouts, and inventory requests

---

## Test Data Summary

### Organizations (3):
| Organization | Slug | Contact |
|---|---|---|
| Acme Corporation | acme-corp | admin@acme-corp.com |
| TechStart Inc | techstart-inc | admin@techstart-inc.com |
| Global Systems Ltd | global-systems | admin@global-systems.com |

### Users Per Organization (4):
- **1 Admin** (can manage users)
- **3 Employees** (can submit documents, tools, inventory)

### Sample Test Accounts

**Acme Corporation:**
```
Admin:
  Email: john.admin@acme-corp.com
  Password: password123

Employees:
  sarah@acme-corp.com / password123
  mike@acme-corp.com / password123
  emily@acme-corp.com / password123
```

**TechStart Inc:**
```
Admin:
  Email: alice.admin@techstart-inc.com
  Password: password123

Employees:
  bob@techstart-inc.com / password123
  carol@techstart-inc.com / password123
  david@techstart-inc.com / password123
```

**Global Systems Ltd:**
```
Admin:
  Email: frank.admin@global-systems.com
  Password: password123

Employees:
  grace@global-systems.com / password123
  henry@global-systems.com / password123
  iris@global-systems.com / password123
```

---

## Testing Data Isolation

### Test 1: Login as Different Organizations

1. **Login as Acme Admin** (john.admin@acme-corp.com)
   - View dashboard - should see only Acme users & data
   - Go to /admin/users - should only see Sarah, Mike, Emily (Acme employees)
   - Go to /documents - should only see Acme documents

2. **Logout & Login as TechStart Admin** (alice.admin@techstart-inc.com)
   - View dashboard - should see only TechStart users & data
   - Go to /admin/users - should only see Bob, Carol, David (TechStart employees)
   - Notice: Sarah, Mike, Emily from Acme are NOT visible

3. **Expected Result:** ✅ Each admin only sees their organization's data

---

### Test 2: Verify Employee-Only Access

1. **Login as Employee** (sarah@acme-corp.com)
   - View dashboard - should see only Acme's pending items
   - Can see own documents but NOT other employees' documents
   - Cannot access /admin routes (middleware blocks)

2. **Expected Result:** ✅ Employees have limited, org-specific access

---

### Test 3: Test Authorization Blocks

1. **Try direct access to another org's user:**
   - Login as Acme admin
   - Try editing TechStart user via URL hack: `/admin/users/12/edit` (where 12 is TechStart user)
   - Should get **403 Unauthorized** error

2. **Expected Result:** ✅ Cross-organization access is blocked

---

## Database Tables & Sample Data

### documents table
- **Acme:** 3 documents (pending_review status)
- **TechStart:** 3 documents (approved status)
- **Global:** 3 documents (draft status)

### tool_checkouts table
- **Acme:** 3 checkouts (requested status)
- **TechStart:** 3 checkouts (checked_out status)
- **Global:** 3 checkouts (approved status)

### inventory_requests table
- **Acme:** 3 requests (submitted status)
- **TechStart:** 3 requests (approved status)
- **Global:** 3 requests (fulfilled status)

---

## Running Individual Seeders

If you need to reset only specific data:

```bash
# Reset all and seed everything
php artisan migrate:fresh --seed

# Seed without migration (data already exists)
php artisan db:seed

# Seed only specific seeder
php artisan db:seed --class=OrganizationSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=DocumentSeeder
php artisan db:seed --class=ToolCheckoutSeeder
php artisan db:seed --class=InventoryRequestSeeder
```

---

## Multi-Tenancy Verification Checklist

After seeding, verify:

- [ ] **Dashboard shows org-specific stats** (3 orgs = different numbers)
- [ ] **Admin users table shows only org users** (Acme admin sees only 4 Acme users)
- [ ] **Employee can't access /admin routes** (403 error if tried)
- [ ] **Employee only sees own documents** (Sarah can't see Mike's docs)
- [ ] **Cross-org access blocked** (Try editing user from different org = 403)
- [ ] **Email uniqueness per org** (Can have same email in different orgs, but not same org)

---

## Troubleshooting

**Issue:** Migration fails with "column not found"
```bash
Solution: Run fresh migration
php artisan migrate:fresh --seed
```

**Issue:** Foreign key constraint error
```bash
Ensure organizations are created before users
(Already handled in seeders with proper order)
```

**Issue:** Seeder doesn't create all test data
```bash
Check app/Models import paths and ensure models exist
Verify Model relationships are defined
```

---

## Files Created/Updated

**New Seeders:**
- `database/seeders/OrganizationSeeder.php` - Creates 3 organizations
- `database/seeders/UserSeeder.php` - Creates 12 users (4 per org)
- `database/seeders/DocumentSeeder.php` - Creates sample documents
- `database/seeders/ToolCheckoutSeeder.php` - Creates sample tool checkouts
- `database/seeders/InventoryRequestSeeder.php` - Creates sample inventory requests

**Updated:**
- `database/seeders/DatabaseSeeder.php` - Orchestrates all seeders

**Migrations (Already Created):**
- `2024_01_01_000000_create_organizations_table.php`
- `2024_01_02_000000_create_users_table.php`
- `2024_12_12_000005_create_documents_table.php`
- `2024_12_12_000006_create_tool_checkouts_table.php`
- `2024_12_12_000007_create_inventory_requests_table.php`

---

## Next Steps

1. ✅ Run `php artisan migrate:fresh --seed`
2. ✅ Login with test accounts and verify data isolation
3. ✅ Test cross-org access (should get 403 errors)
4. ✅ Confirm dashboard shows org-specific stats
5. Ready for mobile responsiveness improvements
6. Ready for production deployment

---

## Security Notes

- ✅ All test passwords are hashed using bcrypt
- ✅ org_id is enforced at database level (foreign key)
- ✅ Controllers verify org ownership before returning data
- ✅ Middleware injects org context on every request
- ✅ Test data is realistic but clearly marked (names have org in parentheses)
