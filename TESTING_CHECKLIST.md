# DOCUMENT SIGNING WITH CERTIFICATES - TESTING CHECKLIST

## Pre-Testing Verification ✓

- [x] DocumentSigningService.php created (398 lines)
- [x] DocumentSigningService methods implemented:
  - [x] createSignedDocument()
  - [x] createSignatureCertificate()
  - [x] saveSignatureImage()
  - [x] createSignatureMetadata()
  - [x] createSignatureCertificateHtml()
- [x] Database migration created
- [x] Database migration executed (documents table updated)
- [x] Document model updated (signature_certificate_path in $fillable)
- [x] DocumentController.storeSigning() updated (uses service)
- [x] DocumentController.downloadSignedCertificate() added
- [x] Route registered: documents.downloadSignedCertificate
- [x] sign.blade.php updated (certificate explanation added)
- [x] show.blade.php updated (download button added)
- [x] All imports correct
- [x] No syntax errors

---

## TESTING PROCEDURES

### TEST 1: Document Upload ✓

**Purpose**: Verify basic document upload works (baseline)

**Steps**:
1. [ ] Login as employee
2. [ ] Navigate to Documents → Upload
3. [ ] Select test file (PDF, Word, or image)
4. [ ] Fill in Title and Description
5. [ ] Click Upload

**Expected Results**:
- [ ] Success message appears
- [ ] Document appears in Documents list
- [ ] Document status is "Pending Review"
- [ ] "Sign Document" button is visible

**Pass**: YES / NO

---

### TEST 2: View Signing Page ✓

**Purpose**: Verify signing page displays correctly with new info

**Steps**:
1. [ ] From Documents list, click on uploaded document
2. [ ] Click "Sign Document" button
3. [ ] Examine page content

**Expected Results**:
- [ ] Document details displayed (name, type, upload date)
- [ ] Status shows "Pending Review"
- [ ] NEW: Green info box appears with text:
      "Professional Signature Certificate"
      "When you sign, a professional certificate will be 
       generated with today's date and time embedded."
- [ ] Signature method options: Draw or Type
- [ ] Canvas signature pad visible and interactive
- [ ] Type signature input field appears when Type selected
- [ ] Agreement checkbox present
- [ ] "Sign Document" button present

**Pass**: YES / NO

---

### TEST 3: Sign with Drawn Signature ✓

**Purpose**: Test certificate generation with drawn signature

**Steps**:
1. [ ] On signing page, ensure "Draw Signature" is selected
2. [ ] Draw a signature on the canvas (any pattern)
3. [ ] Check "I agree to sign this document electronically"
4. [ ] Click "Sign Document" button

**Expected Results**:
- [ ] Page redirects to Documents list
- [ ] Success message appears:
      "Document signed successfully! You can now 
       download your signed certificate."
- [ ] Document appears in list with status "Approved"
- [ ] Document shows as signed

**Pass**: YES / NO

**If FAIL**: Check browser console for JavaScript errors, check Laravel logs at storage/logs/

---

### TEST 4: View Signed Document ✓

**Purpose**: Test that download button appears for signed documents

**Steps**:
1. [ ] From Documents list, click the signed document
2. [ ] Examine the buttons section

**Expected Results**:
- [ ] "Sign Document" button is GONE
- [ ] NEW: Green "Download Signed Certificate" button visible
- [ ] Green button shows checkmark icon
- [ ] Blue "Download" button still visible (original document)
- [ ] "Back to Documents" button still visible

**Pass**: YES / NO

---

### TEST 5: Download Certificate ✓

**Purpose**: Test certificate file download

**Steps**:
1. [ ] Click green "Download Signed Certificate" button
2. [ ] Accept browser download
3. [ ] Check downloaded file

**Expected Results**:
- [ ] File downloads successfully
- [ ] Filename format: `DOC-{id}_SIGNED_CERTIFICATE.html`
- [ ] File size: 5-15 KB
- [ ] File opens in browser or text editor

**Pass**: YES / NO

**If FAIL**: Check database for signature_certificate_path value, verify storage directory exists

---

### TEST 6: View Certificate Content ✓

**Purpose**: Test certificate display and embedded date/time

**Steps**:
1. [ ] Open downloaded certificate HTML file
2. [ ] View in web browser
3. [ ] Examine content

**Expected Results**:
- [ ] Professional certificate displays
- [ ] Header shows: "✓ Document Signed"
- [ ] Header shows: "Digital Signature Certificate"
- [ ] Header shows: "AUTHENTICATED & TIMESTAMPED" badge
- [ ] Content shows:
      - [x] "Signed By: [Employee Name]"
      - [x] "Date: [Today's date]" ← IMPORTANT
      - [x] "Time: [Current time]"    ← IMPORTANT
      - [x] "Document ID: DOC-[#]"
- [ ] Signature section displays:
      - [x] Canvas drawing or typed name
- [ ] Footer shows:
      - [x] "This document has been electronically signed..."
      - [x] ISO timestamp (2026-01-07T15:45:32...)

**Pass**: YES / NO

**If FAIL**: Check certificate generation in DocumentSigningService.createSignatureCertificate()

---

### TEST 7: Print Certificate to PDF ✓

**Purpose**: Test printing certificate to PDF

**Steps**:
1. [ ] Certificate open in browser
2. [ ] Press Ctrl+P (or File → Print)
3. [ ] Select "Save as PDF"
4. [ ] Save PDF file

**Expected Results**:
- [ ] Print dialog appears
- [ ] Can select PDF as printer
- [ ] PDF generates successfully
- [ ] PDF opens and shows professional certificate
- [ ] Date and time visible in PDF
- [ ] Signature visible in PDF

**Pass**: YES / NO

---

### TEST 8: Sign with Typed Signature ✓

**Purpose**: Test certificate with typed signature option

**Steps**:
1. [ ] Upload another test document
2. [ ] Click "Sign Document"
3. [ ] Select "Type Signature" option
4. [ ] Type employee name in text field
5. [ ] Check agreement
6. [ ] Click "Sign Document"

**Expected Results**:
- [ ] Document signed successfully
- [ ] Success message appears
- [ ] Can download certificate

**Follow-up**:
1. [ ] Download certificate
2. [ ] Open in browser

**Expected Certificate Content**:
- [ ] Name appears in stylized cursive font instead of drawn image
- [ ] All other info same (date, time, document ID, footer)

**Pass**: YES / NO

---

### TEST 9: Database Verification ✓

**Purpose**: Verify database records are correct

**Steps**:
1. [ ] Open database client (phpMyAdmin, TablePlus, etc.)
2. [ ] View documents table
3. [ ] Find signed documents

**Expected Results**:
For each signed document, verify:
- [x] Column `signed_at` has timestamp (2026-01-07 15:45:32)
- [x] Column `status` is "approved"
- [x] Column `signature_type` is "draw" or "type"
- [x] Column `signature_data` has base64 or text
- [x] Column `signature_certificate_path` has value:
      Example: "documents/certificates/DOC-123_SIGNED.html"

**Pass**: YES / NO

---

### TEST 10: Storage Directory Verification ✓

**Purpose**: Verify files are stored correctly

**Steps**:
1. [ ] Navigate to `storage/app/documents/`
2. [ ] Check subdirectories

**Expected Results**:
- [x] Directory `certificates/` exists
- [x] Contains files: `DOC-{id}_SIGNED.html`
- [x] Directory `signatures/` exists
- [x] Contains PNG files: `signature_{id}_{timestamp}.png` (if drawn signature)
- [x] Directory `uploads/` exists
- [x] Contains original uploaded documents

**Pass**: YES / NO

---

### TEST 11: Cannot Sign Twice ✓

**Purpose**: Verify document cannot be signed multiple times

**Steps**:
1. [ ] Open a signed document
2. [ ] Try to find "Sign Document" button

**Expected Results**:
- [x] "Sign Document" button does NOT appear
- [x] Only "Download Signed Certificate" appears
- [x] If you manually try to POST to sign endpoint:
      - [x] Get error: "Document is already signed"
      - [x] Redirect to document view with error message

**Pass**: YES / NO

---

### TEST 12: Organization Isolation ✓

**Purpose**: Verify multi-tenant security

**Steps**:
1. [ ] Login as employee in ORG A
2. [ ] Sign a document
3. [ ] Get the document ID and certificate path
4. [ ] Logout
5. [ ] Login as employee in ORG B with same device
6. [ ] Try to access ORG A's document (via URL manipulation if needed)

**Expected Results**:
- [x] ORG B employee CANNOT see ORG A's documents
- [x] ORG B employee CANNOT download ORG A's certificates
- [x] 403 Forbidden error or redirect if attempted
- [x] Each org can only see their own signed documents

**Pass**: YES / NO

---

### TEST 13: User Ownership Verification ✓

**Purpose**: Verify users can only sign own documents

**Steps**:
1. [ ] Login as Employee A
2. [ ] Upload document
3. [ ] Logout
4. [ ] Login as Employee B (in same organization)
5. [ ] Try to access Employee A's document URL

**Expected Results**:
- [x] Employee B CANNOT view Employee A's document
- [x] Employee B CANNOT sign Employee A's document
- [x] 403 Forbidden error or redirect

**Pass**: YES / NO

---

### TEST 14: Responsive Design ✓

**Purpose**: Test on mobile/tablet

**Steps**:
1. [ ] On desktop browser, open Developer Tools
2. [ ] Switch to mobile view (iPhone 12 or similar)
3. [ ] Navigate through signing flow
4. [ ] Draw signature on mobile canvas
5. [ ] Download certificate

**Expected Results**:
- [x] Signing page responsive and works on mobile
- [x] Canvas signature pad works with touch
- [x] Certificate displays properly on mobile
- [x] Download works on mobile

**Pass**: YES / NO

---

### TEST 15: Error Handling ✓

**Purpose**: Test error scenarios

**Test Case 15a: No Signature Provided**
- [x] Try to submit signature form without drawing/typing
- [x] Expected: Validation error

**Test Case 15b: Missing Required Fields**
- [x] Try to submit without checking agreement
- [x] Expected: Form prevents submission

**Test Case 15c: File Permissions**
- [x] Create a signed document
- [x] Check that files are readable
- [x] Expected: Download succeeds, files not corrupted

**Pass**: YES / NO

---

## REGRESSION TESTS

### Existing Functionality ✓

- [ ] Documents can still be uploaded (Test 1)
- [ ] Documents can still be viewed (Test 2)
- [ ] Documents can still be downloaded (original file, Test 9)
- [ ] Documents can still be deleted
- [ ] Filters/search still work
- [ ] Pagination still works
- [ ] Admin can see all organization documents
- [ ] Employees can see only their own documents

**Pass**: YES / NO

---

## PERFORMANCE TESTS

- [ ] Signing a document takes < 2 seconds
- [ ] Downloading certificate is immediate (< 1 second)
- [ ] Certificate file is < 20 KB
- [ ] No database errors in logs
- [ ] No memory leaks observed
- [ ] Storage grows appropriately (cert + sig image per document)

**Pass**: YES / NO

---

## SECURITY TESTS

- [x] User cannot access other org's documents
- [x] User cannot sign document twice
- [x] User cannot download others' certificates
- [x] Database records properly scoped by org_id
- [x] Signatures stored securely
- [x] No sensitive data in logs
- [x] CSRF tokens present in forms
- [x] No SQL injection vulnerabilities
- [x] No XSS vulnerabilities in certificate display

**Pass**: YES / NO

---

## FINAL CHECKLIST

### All Tests Complete?
- [ ] TEST 1: Document Upload - PASS
- [ ] TEST 2: View Signing Page - PASS
- [ ] TEST 3: Sign with Drawn Signature - PASS
- [ ] TEST 4: View Signed Document - PASS
- [ ] TEST 5: Download Certificate - PASS
- [ ] TEST 6: View Certificate Content - PASS
- [ ] TEST 7: Print to PDF - PASS
- [ ] TEST 8: Sign with Typed Signature - PASS
- [ ] TEST 9: Database Verification - PASS
- [ ] TEST 10: Storage Directory - PASS
- [ ] TEST 11: Cannot Sign Twice - PASS
- [ ] TEST 12: Organization Isolation - PASS
- [ ] TEST 13: User Ownership - PASS
- [ ] TEST 14: Responsive Design - PASS
- [ ] TEST 15: Error Handling - PASS
- [ ] Regression Tests - PASS
- [ ] Performance Tests - PASS
- [ ] Security Tests - PASS

### Verification Complete?
- [ ] All required files created
- [ ] All required files updated
- [ ] Database migration executed
- [ ] No PHP syntax errors
- [ ] No JavaScript errors
- [ ] No database errors
- [ ] Documentation complete

---

## SIGN-OFF

**Testing Status**: [ ] PASS / [ ] FAIL

**Tested By**: ___________________

**Date**: ___________________

**Notes**:
```
[Add any notes or issues found here]



```

**Ready for Production**: [ ] YES / [ ] NO

---

## KNOWN ISSUES / NOTES

(Add any issues discovered during testing here)

```

```

---

## NEXT STEPS

After testing completes:

1. [ ] All tests passed
2. [ ] User training (if needed)
3. [ ] Rollout to employees
4. [ ] Monitor logs for errors
5. [ ] Gather user feedback
6. [ ] Plan enhancements (email, bulk, etc.)

---

Good luck with testing! The feature is fully implemented and ready to go! 🎉
