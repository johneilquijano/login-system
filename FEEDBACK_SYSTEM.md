# Feedback / Report Issue System Documentation

## Overview
A comprehensive in-app feedback and bug reporting system that allows employees and admins to report issues and provide feedback. Users can click on the exact area they want to report, add a description, and admins can manage all feedback through a dedicated inbox.

## Features

### User Experience (Employee & Admin)

#### 1. Floating Feedback Button
- **Location:** Bottom-right corner of the screen
- **Visible to:** All authenticated users (employees & admins)
- **Style:** Blue floating button with icon
- **Interaction:** Click to enter capture mode

#### 2. Click-to-Pin Capture Mode
When user clicks the Feedback button:
- Enter capture mode with overlay showing instructions
- "Click the area you want to report" guidance
- Prevent underlying UI actions (no form submissions, no navigation)
- Capture mode exits on:
  - User clicks target element
  - User clicks Cancel button
  - User presses ESC key

#### 3. Feedback Modal
After clicking target element:
- **Category** (required): Bug | UX | Feature Request
- **Description** (required): Multi-line text input (max 1000 chars)
- **Severity** (optional): Low / Medium / High
- **Context Summary** (read-only):
  - Current page/route
  - Clicked element label
  - Click coordinates
  - Viewport size

#### 4. Automatic Data Capture
All feedback automatically records:

**Request Context:**
- `user_id` - User who submitted feedback
- `user_role` - Employee or Admin
- `org_id` - Organization (multi-tenant support)
- `route` - Named route if available
- `url_path` - Full URL path (e.g., /admin/inventory-requests)
- `created_at` - Submission timestamp
- `viewport_width`, `viewport_height` - Screen dimensions
- `user_agent` - Browser/device info

**Click Coordinates:**
- `click_x`, `click_y` - Relative to viewport
- `scroll_x`, `scroll_y` - Page scroll position

**Element Metadata (Best-Effort):**
- `element_tag` - HTML tag (button, input, div, etc.)
- `element_id` - Element ID if available
- `element_name` - Element name attribute
- `element_classes` - CSS classes
- `element_text` - Text content (trimmed to 255 chars)
- `element_aria_label` - Accessibility label
- `element_placeholder` - Input placeholder
- `element_selector` - CSS selector (best-effort)
- `element_path` - Simplified DOM path

### Admin Feedback Inbox

#### Navigation
- **Location:** Admin Sidebar → System → Feedback Inbox
- **Route:** `/admin/feedback`
- **Access:** Admin only

#### List View (`/admin/feedback`)
Shows all feedback with:

**Columns:**
- Status badge (New / In Review / Fixed / Ignored)
- Category (Bug / UX / Feature)
- Message preview (truncated)
- Page/Route
- Reporter name + role
- Submission date/time

**Filters:**
- Status (dropdown)
- Category (dropdown)
- Reporter/Employee (dropdown)
- Page/Route (search input)
- Date range (from/to)

**Statistics Widget:**
- Total feedback count
- New count
- In Review count
- Fixed count
- Ignored count

**Actions:**
- Click row to view details
- Auto-submit filters on change

#### Detail View (`/admin/feedback/{id}`)
Full feedback context display:

**Main Panel:**
- Full message text
- Category label
- Status badge with controls
- Reporter info + email
- Submission timestamp
- Severity (if set)

**Click Context Section:**
- Exact click coordinates (X, Y)
- Scroll position (X, Y)
- Viewport size
- Page URL

**Element Metadata Section:**
- All captured element data
- CSS selectors
- DOM path
- Text content
- ARIA labels

**Admin Notes:**
- Multi-line text area
- Auto-save on submit
- "Saved" confirmation

**Status Controls (Right Sidebar):**
- 4 buttons for status updates:
  - 🆕 New
  - 👀 In Review
  - ✅ Fixed
  - 🚫 Ignored
- Click button to update status
- Instant update (no page reload needed)

**Actions:**
- Delete Feedback button
- Confirmation dialog before deletion

#### Feedback Information Card
Shows metadata:
- User name
- User role (Employee/Admin badge)
- Organization name
- Feedback ID
- Exact submission timestamp

## Data Model

### Feedback Table Fields

```php
id                      // Primary key
user_id                 // Foreign key to users
org_id                  // Foreign key to organizations
user_role               // Enum: employee, admin
route                   // Named route (nullable)
url_path                // Full URL path
viewport_width          // Screen width
viewport_height         // Screen height
user_agent              // Browser/device info
click_x                 // Click X coordinate
click_y                 // Click Y coordinate
scroll_x                // Scroll X position
scroll_y                // Scroll Y position
element_tag             // HTML tag name
element_id              // Element ID
element_name            // Element name attribute
element_classes         // CSS classes
element_text            // Element text content
element_aria_label      // Accessibility label
element_placeholder     // Input placeholder
element_selector        // CSS selector
element_path            // DOM path
category                // Enum: bug, ux, feature
message                 // User's feedback text
severity                // Enum: low, medium, high (nullable)
status                  // Enum: new, in_review, fixed, ignored (default: new)
admin_notes             // Internal admin notes (nullable)
screenshot_url          // Future: screenshot storage (nullable)
screenshot_captured     // Future: whether screenshot exists (default: false)
created_at              // Submission timestamp
updated_at              // Last update timestamp
```

### Indexes
- `org_id, status` - For inbox filtering
- `org_id, category` - For category filtering
- `user_id, created_at` - For user feedback history
- `url_path` - For page-based filtering
- `created_at` - For recent feedback

## API Routes

### Employee/Admin Routes
- `POST /feedback` - Submit feedback (FeedbackController@store)

### Admin-Only Routes
- `GET /admin/feedback` - Feedback inbox list (AdminFeedbackController@index)
- `GET /admin/feedback/{feedback}` - Feedback detail (AdminFeedbackController@show)
- `POST /admin/feedback/{feedback}/status` - Update status (AdminFeedbackController@updateStatus)
- `POST /admin/feedback/{feedback}/notes` - Update admin notes (AdminFeedbackController@updateNotes)
- `DELETE /admin/feedback/{feedback}` - Delete feedback (AdminFeedbackController@destroy)

## Controllers

### FeedbackController (app/Http/Controllers/)
**Purpose:** Handle feedback submission from employees and admins

**Methods:**
- `store(Request $request)` - Validate and store feedback

**Validation Rules:**
```php
'category' => 'required|in:bug,ux,feature'
'message' => 'required|string|max:1000'
'severity' => 'nullable|in:low,medium,high'
'route' => 'nullable|string|max:255'
'url_path' => 'required|string|max:500'
'viewport_width' => 'nullable|integer|min:1'
'viewport_height' => 'nullable|integer|min:1'
'user_agent' => 'nullable|string|max:500'
'click_x' => 'nullable|integer|min:0'
'click_y' => 'nullable|integer|min:0'
'scroll_x' => 'nullable|integer|min:0'
'scroll_y' => 'nullable|integer|min:0'
'element_tag' => 'nullable|string|max:50'
'element_id' => 'nullable|string|max:255'
'element_classes' => 'nullable|string|max:500'
'element_text' => 'nullable|string|max:255'
'element_aria_label' => 'nullable|string|max:255'
'element_placeholder' => 'nullable|string|max:255'
'element_selector' => 'nullable|string|max:1000'
'element_path' => 'nullable|string|max:1000'
```

### Admin\FeedbackController (app/Http/Controllers/Admin/)
**Purpose:** Manage feedback inbox and responses

**Methods:**
- `index(Request $request)` - List all feedback with filtering
- `show(Feedback $feedback)` - Display feedback detail
- `updateStatus(Request $request, Feedback $feedback)` - Change feedback status
- `updateNotes(Request $request, Feedback $feedback)` - Update admin notes
- `destroy(Feedback $feedback)` - Delete feedback

**Features:**
- Multi-org support (filters by Auth::user()->org_id)
- Advanced filtering (status, category, page, date range, reporter)
- Statistics calculation
- CSRF protection on all mutations

## Model (app/Models/Feedback.php)

**Relationships:**
- `user()` - Belongs to User
- `organization()` - Belongs to Organization

**Scopes:**
- `forOrganization($orgId)` - Filter by organization
- `byStatus($status)` - Filter by status
- `byCategory($category)` - Filter by category
- `byPage($urlPath)` - Search page/route
- `byReporter($userId)` - Filter by reporter
- `byDateRange($fromDate, $toDate)` - Date range filter
- `recent($days = 30)` - Recent feedback
- `orderByRecent()` - Order by newest first

**Accessors:**
- `category_label` - Human-readable category
- `status_label` - Human-readable status
- `severity_label` - Human-readable severity

**Methods:**
- `markAsReview()` - Set status to in_review
- `markAsFixed()` - Set status to fixed
- `markAsIgnored()` - Set status to ignored
- `updateAdminNotes($notes)` - Update admin notes

## Frontend Components

### Feedback Widget (`resources/views/components/feedback-widget.blade.php`)
Complete feedback system UI with:
- Floating action button (bottom-right)
- Capture mode overlay
- Feedback submission modal
- JavaScript handlers for all interactions
- AJAX submission
- Toast notifications

**Key JavaScript Functions:**
- `openFeedbackModal()` - Open feedback button
- `enterCaptureMode()` - Start click-to-pin
- `exitCaptureMode()` - Cancel capture mode
- `closeFeedbackModal()` - Close modal
- `getElementSelector(element)` - Generate CSS selector
- `getElementPath(element)` - Generate DOM path
- `showToast(message, type)` - Show notification

## Security Considerations

✅ **Implemented:**
- CSRF token on all form submissions
- User scope validation (users can only submit their own feedback)
- Organization scope validation (admins only see their org's feedback)
- No form input values captured (only element metadata)
- No password fields accessible
- Authorization checks on all admin routes

⚠️ **Not Captured:**
- Form field values (to prevent sensitive data leakage)
- Input passwords or hidden tokens
- User passwords or credentials

## Future Enhancements (Out of Scope for Now)

### Screenshot Capture
- Database fields already prepared (`screenshot_url`, `screenshot_captured`)
- Add screenshot library (e.g., html2canvas)
- Implement client-side screenshot on click
- Add image upload/storage to S3 or local disk
- Display screenshot in admin detail view

### Email Notifications
- Notify admins when new feedback submitted
- Notification settings per org

### Feedback Analytics
- Dashboard charts: feedback by category, status, time
- Trend analysis
- Most reported pages

### Auto-Tagging
- ML-based category suggestion
- Severity auto-detection

### User Communication
- Add comment/reply system for admin-to-user communication
- Notify users when feedback status changes

## Testing Checklist

- [ ] Employee can open feedback button
- [ ] Capture mode overlays page correctly
- [ ] ESC key exits capture mode
- [ ] Clicking element captures metadata
- [ ] Modal opens with correct context
- [ ] Form validation works
- [ ] Feedback submits without page reload
- [ ] Success toast appears
- [ ] Admin inbox lists all feedback
- [ ] Filters work (status, category, page, date range)
- [ ] Statistics update correctly
- [ ] Admin can view feedback detail
- [ ] Admin can update status
- [ ] Admin can update notes
- [ ] Admin can delete feedback
- [ ] Multi-org isolation works
- [ ] Routes are properly protected

## Dependencies

- Laravel 10+
- Tailwind CSS (for styling)
- PHP 8.2+
- Modern browser (ES6+ support for JavaScript)

## Installation

1. **Create migration:**
   ```bash
   php artisan migrate
   ```

2. **No seeders needed** - Feedback is user-generated

3. **Verify routes:**
   ```bash
   php artisan route:list | grep feedback
   ```

4. **Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

5. **Test in browser:**
   - Login as employee or admin
   - Look for blue feedback button (bottom-right)
   - Click to test capture mode

## File Locations

```
app/
  Models/
    Feedback.php
  Http/
    Controllers/
      FeedbackController.php
      Admin/
        FeedbackController.php
routes/
  web.php (feedback routes)
resources/
  views/
    components/
      feedback-widget.blade.php
    admin/
      feedback/
        index.blade.php
        show.blade.php
database/
  migrations/
    2026_01_14_create_feedbacks_table.php
```

## Notes

- All timestamps are in user's timezone (if configured)
- Feedback is permanently stored for audit trail
- No automatic cleanup of old feedback
- Element metadata is best-effort (not guaranteed to be stable across page reloads)
- Screenshot fields prepared for future use
