# Partial Receiving & Auto-Fulfill Workflow

## Overview
The ordering task system now supports partial receiving with automatic request fulfillment. Items can be received in multiple shipments, and when all approved quantities are received, the parent request automatically fulfills.

## System Architecture

### Data Flow
1. **Employee submits request** with items including job_number and model_number
2. **Admin approves request** → Auto-creates OrderingTask (one open per org) with OrderingTaskItems
3. **Admin marks items as "ordered"** → Updates status to 'ordered'
4. **Admin receives items** (partial or full) → Updates quantity_received, auto-updates status
5. **When all items fully received** → Request auto-fulfills, sends notification to employee

### Status Progression

**OrderingTaskItem statuses:**
- `pending` - Created when request approved, awaiting order
- `ordered` - Admin marked as ordered
- `partially_received` - Some quantity received (qty_received < qty_approved)
- `received` - Full quantity received (qty_received >= qty_approved)

**InventoryRequest statuses:**
- `submitted` → `approved` (on approval)
- `approved` → `fulfilled` (when all task items fully received)

## Database Changes

### Migration Applied
File: `database/migrations/2026_01_10_000003_add_partial_receiving_to_ordering_task_items.php`

**Changes:**
- Added `quantity_received` column (INT, default 0) to `ordering_task_items`
- Updated `status` enum to include 'partially_received': `['pending', 'ordered', 'partially_received', 'received']`

### Table Structure
```
ordering_task_items:
- id (primary key)
- org_id (foreign key)
- ordering_task_id (foreign key)
- inventory_request_id (foreign key)
- inventory_request_item_id (foreign key)
- item_name
- job_number
- model_number
- quantity_approved (total approved for this request)
- quantity_received (total received so far) ← NEW
- status (enum: pending|ordered|partially_received|received) ← UPDATED
- notes
- ordered_at (nullable)
- received_at (nullable)
- created_at / updated_at
```

## Code Changes

### Model: OrderingTaskItem
**New Methods:**
- `receiveItems($quantity, $notes = null)` - Records a receipt, auto-updates status
- `getRemainingQuantity()` - Returns qty_approved - qty_received
- `isFullyReceived()` - Checks if qty_received >= qty_approved
- `getProgressPercentage()` - Returns (qty_received / qty_approved) * 100

**Behavior:**
- Status auto-updates based on quantity_received:
  - If qty_received < qty_approved → status = 'partially_received'
  - If qty_received >= qty_approved → status = 'received'

### Controller: OrderingTaskController
**Updated Methods:**
- `receive($itemId, Request $request)` - Handles partial receiving
  - Validates: receive_quantity, receive_date (optional), receive_notes (optional)
  - Calls `receiveItems()` to record receipt
  - Calls `checkAndFulfillRequest()` to potentially auto-fulfill

**New Helper:**
- `checkAndFulfillRequest($inventoryRequest)` - Auto-fulfills when all items fully received
  - Checks each inventory request item has corresponding OrderingTaskItem fully received
  - Updates request status to 'fulfilled' and fulfilled_at timestamp
  - Sends `InventoryRequestFulfilledNotification` to employee
  - Logs auto-fulfillment

### Routes
**Updated:**
- POST `/admin/ordering-task-items/{id}/receive` - Partial receive endpoint (was `/mark-received`)
- POST `/admin/ordering-task-items/{id}/mark-ordered` - Unchanged
- DELETE `/admin/ordering-task-items/{id}` - Cancel item (unchanged)

### View: admin/ordering-tasks/show.blade.php
**Updated Display:**
- **Quantity Column:** Shows "6 / 10" with progress bar
  - Green progress bar shows percentage complete
  - "+2 over" badge if quantity_received > quantity_approved
  
- **Status Column:** Updated to show 'partially_received' with orange badge
  
- **Actions Column:**
  - "Mark Ordered" button for pending items
  - "Receive" button for ordered/partially_received items
  - "Receive More" button for fully received items
  - "Cancel" button always available

**New Modal:**
- Receive Modal with fields:
  - Quantity to Receive (required, min 1)
  - Receive Date (optional, defaults to today)
  - Notes (optional)
  - Status info showing approved/received/remaining quantities

**New JavaScript:**
- `openReceiveModal(itemId, approved, received)` - Opens modal with pre-filled context
- `submitReceive(e)` - Submits form to `/receive` endpoint via fetch
- `closeReceiveModal()` - Closes modal
- Modal closes on outside click

## Usage Workflow

### Step 1: Employee Submits Request
```
Navigate to Inventory Requests → New Request
- Item Name: "Widget A"
- Quantity: 10
- Job Number: "JOB-2026-001"
- Model Number: "MODEL-ABC-123"
Submit
```

### Step 2: Admin Approves Request
```
Navigate to Admin → Inventory Requests
Click on pending request
Click "Approve"
- Automatically creates OrderingTask if not exists
- Creates OrderingTaskItem with status = 'pending'
```

### Step 3: Admin Views Ordering Task
```
Navigate to Admin → Ordering Tasks
Click on "Ordering Task #1"
- See all pending items
- Status cards show: 1 Pending, 0 Ordered, 0 Received, 1 Total
```

### Step 4: Admin Marks Item as Ordered
```
Click "Mark Ordered" button on item
- Status updates to 'ordered'
- ordered_at timestamp set
```

### Step 5: Admin Receives First Shipment (Partial)
```
Click "Receive" button on item
Modal opens with:
- Approved: 10
- Already Received: 0
- Still Needed: 10
Enter:
- Quantity to Receive: 6
- Receive Date: 2026-01-10
- Notes: "First shipment received"
Click "Receive Items"
```

**Result:**
- quantity_received = 6
- status = 'partially_received'
- Quantity column shows "6 / 10" with 60% progress bar
- Request still in 'approved' status

### Step 6: Admin Receives Remaining Shipment
```
Click "Receive More" button on same item
Modal opens with:
- Approved: 10
- Already Received: 6
- Still Needed: 4
Enter:
- Quantity to Receive: 4
- Receive Date: 2026-01-10
- Notes: "Final shipment received"
Click "Receive Items"
```

**Result:**
- quantity_received = 10
- status = 'received' (fully received)
- Quantity column shows "10 / 10" with 100% progress bar
- **Auto-fulfillment triggered:**
  - Request status automatically updates to 'fulfilled'
  - Request fulfilled_at timestamp set
  - Employee receives notification: "Your inventory request has been fulfilled. Ready for pickup!"

## Key Features

### 1. Partial Receiving
- Admin can receive items in multiple shipments
- Each receipt tracked separately
- No need to receive full quantity at once

### 2. Progress Tracking
- Visual progress bar shows percentage complete
- "Still Needed" quantity calculated dynamically
- Over-receiving allowed (can receive 12 when 10 approved)

### 3. Auto-Fulfillment
- Triggered when ALL approved items are fully received
- Only affects request status if currently 'approved'
- Automatic notification sent to employee
- Logged for audit trail

### 4. Authorization
- All queries include org_id filtering
- Explicit ID-based queries prevent implicit binding issues
- Users can only access their organization's items

### 5. State Management
- Status auto-updates based on quantities
- Multiple items in single request supported
- Each item tracked independently

## Testing Checklist

- [ ] Create inventory request with multiple items
- [ ] Approve request (OrderingTask auto-created)
- [ ] Verify OrderingTaskItem created for each item
- [ ] Mark items as ordered
- [ ] Receive partial quantity
  - [ ] Status shows 'partially_received'
  - [ ] Progress bar shows correct percentage
- [ ] Receive additional quantity
  - [ ] Status updates to 'received'
  - [ ] Progress bar shows 100%
- [ ] Verify request still 'approved' after first receipt
- [ ] Receive final item
  - [ ] Request status changes to 'fulfilled'
  - [ ] Employee receives notification
  - [ ] fulfilled_at timestamp is set
- [ ] Test over-receiving (receive > approved)
  - [ ] Status still becomes 'received'
  - [ ] Over badge shows "+X over"
- [ ] Cancel items from ordering task
- [ ] Filter ordering tasks by status

## Database Queries Reference

### Get all items for a task
```php
OrderingTaskItem::where('ordering_task_id', $taskId)
    ->where('org_id', $orgId)
    ->get();
```

### Get all items needing receipt
```php
OrderingTaskItem::where('org_id', $orgId)
    ->where('status', 'ordered')
    ->orWhere('status', 'partially_received')
    ->get();
```

### Get progress for a task
```php
$task->items->sum('quantity_approved');
$task->items->sum('quantity_received');
```

### Find overdue receipts
```php
OrderingTaskItem::where('org_id', $orgId)
    ->where('status', '!=', 'received')
    ->where('ordered_at', '<', now()->subDays(30))
    ->get();
```

## Notifications

### InventoryRequestFulfilledNotification
- **Trigger:** Auto-fulfill when all task items received
- **Recipient:** Request employee
- **Message:** "Your inventory request has been fulfilled. Ready for pickup!"
- **Storage:** AppNotification table (via database channel)
- **Delivery:** In-app notification bell and notifications page

## Troubleshooting

### Request not auto-fulfilling
1. Check that all OrderingTaskItems have status = 'received'
2. Verify quantity_received >= quantity_approved for each item
3. Check logs: `php artisan tinker` → `App\Models\InventoryRequest::find($id)->status`

### Modal not appearing
1. Ensure browser console has no JavaScript errors
2. Verify modal ID is 'receiveModal'
3. Check that receiveModal div is not hidden by CSS

### Quantity not updating
1. Verify migration ran: `php artisan migrate:status`
2. Check database: `SELECT * FROM ordering_task_items WHERE id = $id`
3. Verify quantity_received column exists in database

### Over-receiving issues
- Over-receiving is intentional and allowed
- System validates receive_quantity >= 1
- Status transitions based on qty_received vs qty_approved

## Future Enhancements

- [ ] Bulk receive functionality for multiple items
- [ ] Receipt documentation/invoice attachment
- [ ] Email notifications on item received
- [ ] Automatic reorder suggestions
- [ ] Receipt history/audit trail
- [ ] Scheduled expiration for old pending items
- [ ] Integration with purchase orders
