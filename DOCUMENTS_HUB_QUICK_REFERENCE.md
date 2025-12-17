# Employee Documents Hub - Quick Reference

## Page Flow

```
Documents List
    ├── [Upload Document Button] → Upload Form → Success → Back to List
    ├── Search & Filters → Filtered Results
    ├── Table Actions
    │   ├── [Preview] → Show Document Details
    │   ├── [Sign] → Sign Page → Signature Capture → Success
    │   └── [Download] → Download File
    └── Empty State → Upload First Document

```

## Status Colors & Meanings

| Status | Color | Meaning |
|--------|-------|---------|
| Draft | Gray | Document not yet submitted |
| Pending Review | Yellow | Waiting for manager review |
| Approved | Green | Reviewed and approved |
| Rejected | Red | Requires changes |

## Signature Status

| Status | Color | Meaning |
|--------|-------|---------|
| Yes | Yellow | Needs employee signature |
| Signed | Green | Already signed by employee |

## Supported File Types

- `PDF` - Portable Document Format
- `DOC` / `DOCX` - Microsoft Word
- `JPG` / `JPEG` - JPEG Image
- `PNG` - PNG Image
- `GIF` - GIF Image

**Max File Size:** 10 MB

## Search & Filter Example

**Scenario:** Find all pending documents that need your signature

1. **Status Filter** → Select "Pending Review"
2. **Signature Filter** → Select "Needs Signature"
3. **Sort** → Select "Newest First"
4. **Click** → "Apply Filters"

**Result:** Table shows only pending docs requiring your signature, newest first

## Signing Process

### Method 1: Draw Signature
1. Click "Draw Signature" option
2. Use mouse/touch to draw signature in canvas
3. Click "Clear Signature" if needed
4. Check "I agree to sign this document"
5. Click "Sign Document"

### Method 2: Type Signature
1. Click "Type Signature" option
2. Type your name in cursive-styled input
3. Check "I agree to sign this document"
4. Click "Sign Document"

## Upload Process

1. Click "Upload Document" button
2. Enter document title
3. (Optional) Add description
4. Drag-drop file or click to browse
5. Select supported file format
6. Click "Upload Document"
7. Success message displays, redirected to list

## Database Structure

### documents table

```sql
CREATE TABLE documents (
    id BIGINT PRIMARY KEY,
    org_id BIGINT FOREIGN KEY,           -- Organization isolation
    user_id BIGINT FOREIGN KEY,          -- Document owner
    title VARCHAR(255),                  -- Document name
    description TEXT,                    -- Optional details
    file_path VARCHAR(255),              -- Storage path
    file_name VARCHAR(255),              -- Original filename
    mime_type VARCHAR(100),              -- File type
    file_size BIGINT,                    -- File size in bytes
    status ENUM('draft', 'pending_review', 'approved', 'rejected'),
    signed_at TIMESTAMP NULL,            -- When signed
    reviewed_at TIMESTAMP NULL,          -- When reviewed
    reviewed_by BIGINT NULL,             -- Who reviewed
    review_notes TEXT NULL,              -- Review comments
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## URL Routes

```
GET  /documents                    - List documents
GET  /documents/upload             - Upload form
POST /documents                    - Submit upload
GET  /documents/{id}               - View document
GET  /documents/{id}/sign          - Sign form
POST /documents/{id}/sign          - Submit signature
GET  /documents/{id}/download      - Download file
```

## Query Examples

### Get user's documents
```php
$documents = Document::forOrganization($orgId)
    ->where('user_id', $userId)
    ->orderBy('created_at', 'desc')
    ->paginate(10);
```

### Get documents needing signature
```php
$pending = Document::forOrganization($orgId)
    ->where('user_id', $userId)
    ->whereNull('signed_at')
    ->get();
```

### Get approved documents
```php
$approved = Document::forOrganization($orgId)
    ->where('status', 'approved')
    ->whereNotNull('signed_at')
    ->get();
```

## Validation Rules

### Upload
- **title** - Required, max 255 chars
- **description** - Optional
- **document** - Required, file, mimes: pdf/doc/docx/jpg/jpeg/png/gif, max 10MB

### Sign
- **signature_type** - Required, in: draw/type
- **signature_data** - Required, string (base64 for draw, text for type)
- **agreement** - Required checkbox

## Error Handling

### Common Errors

| Error | Cause | Solution |
|-------|-------|----------|
| 403 Forbidden | Trying to access another user's document | Only access your own documents |
| File too large | File exceeds 10MB | Upload a smaller file |
| Invalid format | File type not supported | Use PDF, Word, or Image files |
| Already signed | Trying to sign again | Document is already signed |
| No signature data | Clicked sign without drawing/typing | Draw or type your signature |

## Security Features

✅ Organization data isolation (org_id)  
✅ User ownership verification (user_id)  
✅ File storage in private disk  
✅ MIME type validation  
✅ File size limits  
✅ Authorization checks on every action  
✅ Timestamp recording on all signatures  

## Performance Tips

- **Pagination:** 10 documents per page to reduce load
- **Filters:** Applied server-side before fetching to reduce data transfer
- **File Storage:** Private disk prevents direct URL access, requiring auth check
- **Indexes:** org_id and user_id indexed in database for fast queries

---

**Last Updated:** December 16, 2025
