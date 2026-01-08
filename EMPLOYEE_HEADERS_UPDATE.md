# Employee Pages Header UI Update

## Summary
All employee-facing pages now have a consistent header with **notification bell icon** and **user name** in the top-right corner, matching the dashboard design.

## Changes Made

### 1. Created Reusable Header Component
**File:** `resources/views/components/employee-header.blade.php`

A new Blade component that displays:
- **Left side:** Page title + optional subtitle
- **Right side:** 
  - Notification bell icon (`<x-notification-bell />`)
  - User name and role

**Usage:**
```blade
<x-employee-header 
    title="Page Title" 
    subtitle="Optional subtitle text" 
/>
```

### 2. Updated Employee Pages
All 10 employee pages now use the new `<x-employee-header />` component:

#### Documents Section
- ✅ `resources/views/employee/documents/index.blade.php` - Document list
- ✅ `resources/views/employee/documents/show.blade.php` - Document preview
- ✅ `resources/views/employee/documents/upload.blade.php` - Upload new document
- ✅ `resources/views/employee/documents/sign.blade.php` - Sign document

#### Inventory Requests Section
- ✅ `resources/views/employee/inventory-requests/index.blade.php` - Request list
- ✅ `resources/views/employee/inventory-requests/show.blade.php` - Request details
- ✅ `resources/views/employee/inventory-requests/edit.blade.php` - Edit request

#### Inventory Section
- ✅ `resources/views/employee/inventory/index.blade.php` - Inventory list
- ✅ `resources/views/employee/inventory/create.blade.php` - New inventory request

#### Tools Section
- ✅ `resources/views/employee/tools/index.blade.php` - Tool checkout/check-in

### 3. Notification Bell Component
The `<x-notification-bell />` component displays:
- **Unread badge** showing count of unread notifications
- **Dropdown menu** with recent notifications (last 10)
- **Auto-refresh** every 30 seconds to keep notifications current
- **Click to view all** link to full notifications page

## Features

### Consistent Styling
- All headers use the same CSS classes and spacing
- Sticky positioning (stays at top while scrolling)
- White background with subtle shadow
- Proper z-index layering (z-40)

### Responsive Design
- Flexbox layout with space-between alignment
- Works on all screen sizes
- Notification bell responsive to content

### Accessibility
- Proper semantic HTML (`<header>` tags)
- Clear typography hierarchy (h2 for title)
- Clear user information display

## Before & After

### Before
- Dashboard: Had bell + name header ✓
- Other pages: Inconsistent (some had name only, some had neither)

### After
- **All pages:** Unified header with notification bell + user name ✓

## Testing Checklist
- [ ] Dashboard displays bell + name (verify no regression)
- [ ] Documents list shows bell + name
- [ ] Document preview shows bell + name
- [ ] Upload/sign pages show bell + name
- [ ] Inventory request list shows bell + name
- [ ] Request details/edit show bell + name
- [ ] Tools page shows bell + name
- [ ] Notification bell shows unread count
- [ ] Bell dropdown displays recent notifications
- [ ] Notifications page accessible from bell dropdown

## Notes
- Component is fully reusable - can be extended with more props if needed
- Maintains existing color scheme and styling consistency
- No breaking changes to existing functionality
- All 10 employee pages now have uniform appearance
