# DOCUMENT SIGNING WITH CERTIFICATES - VISUAL ARCHITECTURE

## System Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          EMPLOYEE DOCUMENT SIGNING                           │
└─────────────────────────────────────────────────────────────────────────────┘

                                 EMPLOYEE
                                    │
                    ┌───────────────┼───────────────┐
                    │               │               │
                    ▼               ▼               ▼
                UPLOAD           BROWSE          SIGN
                DOCUMENT         DOCUMENTS       DOCUMENT
                    │               │               │
                    └───────────────┼───────────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  DocumentController   │
                        │                       │
                        │ - index()             │
                        │ - upload()            │
                        │ - sign()              │
                        │ - storeSigning() ◄────┼─── REQUEST: Sign Document
                        │ - download()          │
                        │ - downloadSigned...() │
                        └───────────┬───────────┘
                                    │
                                    ▼
                    ┌───────────────────────────────┐
                    │  Validate Request             │
                    │                               │
                    │ ✓ User ownership             │
                    │ ✓ Organization scope         │
                    │ ✓ Document not signed yet    │
                    │ ✓ Signature data provided    │
                    └───────────┬───────────────────┘
                                │
                                ▼
                    ┌───────────────────────────────┐
                    │ DocumentSigningService        │
                    │                               │
                    │ createSignedDocument()        │
                    │  ├─ Receive signature data    │
                    │  ├─ Call createSignature...() │
                    │  └─ Return cert path          │
                    └───────────┬───────────────────┘
                                │
                    ┌───────────┴───────────┐
                    │                       │
                    ▼                       ▼
        ┌─────────────────────┐  ┌─────────────────────┐
        │ saveSignatureImage()│  │ createSignature...()│
        │                     │  │                     │
        │ Base64 Canvas → PNG │  │ HTML + CSS Template │
        │ Save to storage     │  │                     │
        │ Return path         │  │ Date: Jan 07, 2026  │
        └────────┬────────────┘  │ Time: 03:45 PM      │
                 │               │ Name: John Smith    │
                 │               │ Signature: [image]  │
                 │               │ Doc ID: DOC-123     │
                 │               │                     │
                 │               │ Save to storage     │
                 │               │ Return path         │
                 └───────┬───────┴─────────────────────┘
                         │
                         ▼
                ┌────────────────────────┐
                │ Database Update        │
                │                        │
                │ documents.update([     │
                │   signed_at: now(),    │
                │   signature_type: ..., │
                │   signature_data: ..., │
                │   signature_cert_path: │  ◄─── STORE CERTIFICATE PATH
                │      "docs/certs/..." │
                │ ])                    │
                └────────┬──────────────┘
                         │
                         ▼
                ┌────────────────────────┐
                │ Success Response       │
                │                        │
                │ Redirect to index with │
                │ message:               │
                │ "Document signed!      │
                │  Download your cert"   │
                └────────┬──────────────┘
                         │
                         ▼
                    ┌─────────────┐
                    │ EMPLOYEE    │
                    │             │
                    │ Downloads   │──────┐
                    │ Certificate │      │
                    └─────────────┘      │
                                         │
                    ┌────────────────────┘
                    │
                    ▼
        ┌─────────────────────────────────────┐
        │ Browser opens HTML Certificate      │
        │                                     │
        │ ┌───────────────────────────────┐   │
        │ │   ✓ Document Signed           │   │
        │ │  Digital Signature Certificate │   │
        │ │ AUTHENTICATED & TIMESTAMPED    │   │
        │ ├───────────────────────────────┤   │
        │ │                               │   │
        │ │ Signed By:    John Smith      │   │
        │ │ Date:         Jan 07, 2026    │───┼─── EMBEDDED IN FILE
        │ │ Time:         03:45 PM        │   │
        │ │ Document ID:  DOC-123         │   │
        │ │                               │   │
        │ │ Authorized Signature:         │   │
        │ │                               │   │
        │ │  [Signature Image Here]       │   │
        │ │                               │   │
        │ │ Timestamp: 2026-01-07T15:45...│   │
        │ └───────────────────────────────┘   │
        │                                     │
        │ ┌─────────────────────────────────┐ │
        │ │ Print to PDF  │  Save  │  Close │ │
        │ └─────────────────────────────────┘ │
        └─────────────────────────────────────┘
```

---

## Database Schema Changes

```
BEFORE:
┌────────────────────────────┐
│ documents                  │
├────────────────────────────┤
│ id                    PK   │
│ org_id            FK       │
│ user_id           FK       │
│ title                      │
│ description                │
│ file_path                  │
│ mime_type                  │
│ file_size                  │
│ status                     │
│ signed_at         NULLABLE │
│ signature_type    NULLABLE │
│ signature_data    NULLABLE │
│ created_at                 │
│ updated_at                 │
└────────────────────────────┘

AFTER:
┌──────────────────────────────────┐
│ documents                        │
├──────────────────────────────────┤
│ id                          PK   │
│ org_id                    FK     │
│ user_id                   FK     │
│ title                          │
│ description                    │
│ file_path                      │
│ mime_type                      │
│ file_size                      │
│ status                         │
│ signed_at                NULLABLE │
│ signature_type           NULLABLE │
│ signature_data           NULLABLE │
│ signature_certificate_path NEW   │  ◄─── STORES PATH TO CERTIFICATE
│ created_at                       │
│ updated_at                       │
└──────────────────────────────────┘
```

---

## Storage Directory Structure

```
storage/app/
│
└─ documents/
   │
   ├─ certificates/                    ◄─── HTML CERTIFICATES
   │  └─ DOC-123_SIGNED.html
   │     DOC-124_SIGNED.html
   │     DOC-125_SIGNED.html
   │
   ├─ signatures/                      ◄─── SIGNATURE IMAGES
   │  ├─ signature_123_1234567890.png
   │  ├─ signature_124_1234567891.png
   │  └─ signature_125_1234567892.png
   │
   └─ uploads/                         ◄─── ORIGINAL FILES
      ├─ document_123.pdf
      ├─ document_124.docx
      └─ document_125.png
```

---

## Code Flow Sequence

```
1. EMPLOYEE INITIATES SIGNING
   ┌─────────────────────────────────────┐
   │ GET /documents/{id}/sign            │
   │ → DocumentController.sign()         │
   │ → Blade: sign.blade.php             │
   │   (Show signature pad + info)       │
   └─────────────────────────────────────┘

2. EMPLOYEE SUBMITS SIGNATURE
   ┌──────────────────────────────────────┐
   │ POST /documents/{id}/sign            │
   │ Data: signature_type, signature_data │
   │ → DocumentController.storeSigning()  │
   └──────────────────────────────────────┘

3. SERVICE GENERATES CERTIFICATE
   ┌─────────────────────────────────────────┐
   │ DocumentSigningService instantiated     │
   │                                         │
   │ $signingService->createSignedDocument( │
   │   file_path: "docs/uploads/123.pdf"   │
   │   signature_data: "data:image/png...", │
   │   signature_type: "draw",             │
   │   signer_name: "John Smith",          │
   │   document_id: 123                    │
   │ )                                      │
   │                                         │
   │ → createSignatureCertificate()        │
   │   ├─ Format date: "Jan 07, 2026"      │
   │   ├─ Format time: "03:45 PM"          │
   │   ├─ Create HTML with CSS            │
   │   ├─ Embed signature image           │
   │   └─ Save to storage                 │
   │                                         │
   │ → saveSignatureImage()                │
   │   ├─ Convert base64 to PNG          │
   │   └─ Save to signatures/ dir         │
   │                                         │
   │ Returns: "docs/certificates/DOC-123" │
   └─────────────────────────────────────────┘

4. DATABASE UPDATED
   ┌────────────────────────────────────┐
   │ documents.update([                 │
   │   signed_at: 2026-01-07 15:45:32,  │
   │   signature_type: "draw",          │
   │   signature_data: "data:image/...",│
   │   signature_certificate_path:      │
   │     "docs/certs/DOC-123_SIGNED.html"
   │ ])                                 │
   └────────────────────────────────────┘

5. EMPLOYEE DOWNLOADS CERTIFICATE
   ┌──────────────────────────────────────┐
   │ GET /documents/{id}/download-        │
   │     signed-certificate               │
   │ → DocumentController.                │
   │   downloadSignedCertificate()       │
   │ → Storage::download(cert_path)      │
   │                                      │
   │ File: DOC-123_SIGNED_CERTIFICATE.html
   └──────────────────────────────────────┘

6. CERTIFICATE DISPLAYED IN BROWSER
   ┌─────────────────────────────────────────┐
   │ Browser renders HTML file with CSS      │
   │                                          │
   │ Shows:                                   │
   │ ✓ Document Signed                       │
   │ Signed By: John Smith                   │
   │ Date: Jan 07, 2026        ◄─── EMBEDDED │
   │ Time: 03:45 PM            ◄─── EMBEDDED │
   │ Document ID: DOC-123                    │
   │ [Signature Image]                       │
   │ Timestamp: 2026-01-07T15:45:32Z         │
   │                                          │
   │ Print to PDF or Save As...              │
   └─────────────────────────────────────────┘
```

---

## File Dependencies

```
DocumentController
    │
    ├─ uses → DocumentSigningService
    │          │
    │          ├─ method: createSignedDocument()
    │          ├─ method: createSignatureCertificate()
    │          ├─ method: saveSignatureImage()
    │          └─ uses → Storage (Laravel)
    │
    └─ updates → Document Model
                 │
                 ├─ fillable: signature_certificate_path
                 └─ relationship: organization()

Routes (web.php)
    │
    ├─ GET /documents                → DocumentController.index()
    ├─ POST /documents               → DocumentController.store()
    ├─ GET /documents/{id}           → DocumentController.show()
    ├─ GET /documents/{id}/sign      → DocumentController.sign()
    ├─ POST /documents/{id}/sign     → DocumentController.storeSigning()
    ├─ GET /documents/{id}/download  → DocumentController.download()
    ├─ GET /documents/{id}/download-signed-certificate  → NEW
    │   → DocumentController.downloadSignedCertificate()
    └─ DELETE /documents/{id}        → DocumentController.destroy()

Views
    │
    ├─ layouts/app.blade.php
    │  └─ employee-sidebar
    │  └─ employee-header
    │
    ├─ documents/index.blade.php
    │  └─ list all documents
    │  └─ filters, search, pagination
    │
    ├─ documents/show.blade.php
    │  └─ document details
    │  ├─ [Sign Document] button (if not signed)
    │  └─ [Download Signed Certificate] button ◄─── NEW
    │  └─ [Download] button
    │
    └─ documents/sign.blade.php
       ├─ document info
       ├─ signature pad canvas
       │  └─ signature_pad.js library
       ├─ Professional Signature Certificate info ◄─── NEW
       ├─ typed signature input
       └─ agreement checkbox

Database/Migrations
    │
    └─ 2026_01_07_000002_add_signature_certificate_path_to_documents.php
       ├─ creates: signature_certificate_path column
       └─ up/down methods for reversibility
```

---

## Authentication & Security Flow

```
REQUEST
    │
    ▼
Middleware: auth, employee, organization
    │
    ├─ Verify user is logged in
    ├─ Verify user has employee role
    ├─ Inject organization context
    │
    ▼
DocumentController
    │
    ├─ Get current org_id from Auth::user()->org_id
    │
    ├─ Query: Document::forOrganization($orgId)
    │  (Prevents cross-tenant data access)
    │
    ├─ Verify: $document->org_id === Auth::user()->org_id
    │  (Org scoping)
    │
    ├─ Verify: $document->user_id === Auth::id()
    │  (User ownership)
    │
    ├─ Verify: !$document->signed_at
    │  (Cannot sign twice)
    │
    ▼
Allow Action
    │
    ├─ Generate certificate
    ├─ Update database
    ├─ Return response
    │
    ▼
403 Forbidden or Error (if any check fails)
```

---

## Data Flow: From Canvas to Certificate

```
STEP 1: CANVAS DRAWING
┌─────────────────────────────────────┐
│ Employee draws on HTML canvas       │
│ ↓                                   │
│ Canvas.toDataURL() in JavaScript    │
│ ↓                                   │
│ Base64 string:                      │
│ "data:image/png;base64,iVBORw0KG..."│
└─────────────────────────────────────┘

STEP 2: SUBMISSION
┌─────────────────────────────────────┐
│ POST form with base64 string        │
│ signature_data: "data:image/png..."  │
│ signature_type: "draw"              │
└─────────────────────────────────────┘

STEP 3: SERVICE PROCESSING
┌─────────────────────────────────────┐
│ DocumentSigningService.              │
│   createSignedDocument()            │
│                                     │
│ ├─ saveSignatureImage():            │
│ │  "data:image/png..." → PNG file   │
│ │  Saved: docs/signatures/sig_123...│
│ │                                   │
│ └─ createSignatureCertificate():    │
│    HTML + CSS template              │
│    Embeds PNG as base64 in HTML     │
│    Includes date/time/name          │
│    Saved: docs/certificates/DOC-123 │
└─────────────────────────────────────┘

STEP 4: DOWNLOAD & VIEW
┌─────────────────────────────────────┐
│ HTML file downloaded as:            │
│ DOC-123_SIGNED_CERTIFICATE.html    │
│                                     │
│ Browser renders:                    │
│ <img src="data:image/png;base64,..">│
│ Shows signature inline in document  │
│                                     │
│ User prints/saves PDF from browser  │
└─────────────────────────────────────┘
```

---

## Summary

This architecture provides a complete document signing system with professional timestamped certificates. The service layer handles certificate generation independently, making it easy to:
- Extend with additional certificate formats
- Add email delivery
- Implement expiration workflows
- Support bulk operations
- Add digital signatures

All security measures ensure multi-tenant safety and user accountability.
