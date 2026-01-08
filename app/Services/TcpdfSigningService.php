<?php

namespace App\Services;

use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * TcpdfSigningService - Sign PDFs using FPDI + TCPDF
 * 
 * Imports original PDF content and overlays signature at bottom right.
 */
class TcpdfSigningService
{
    /**
     * Sign a PDF by importing it and adding signature at lower right
     * 
     * @param string $originalPdfPath Path to original PDF in private storage
     * @param string|null $signatureImagePath Path to signature PNG in private storage (null for typed)
     * @param string $signatureData Base64 image for drawn OR plain text for typed
     * @param string $signerName Name of signer
     * @param int $documentId Document ID
     * @return string|null Path to signed PDF or null if failed
     */
    public function signPdf($originalPdfPath, $signatureImagePath, $signatureData, $signerName, $documentId)
    {
        try {
            // Verify original PDF exists
            if (!Storage::disk('private')->exists($originalPdfPath)) {
                \Log::error("Original PDF not found: {$originalPdfPath}");
                return null;
            }

            // For drawn signatures, verify signature image exists
            if ($signatureImagePath && !Storage::disk('private')->exists($signatureImagePath)) {
                \Log::error("Signature image not found: {$signatureImagePath}");
                return null;
            }

            // Get full paths
            $fullPdfPath = Storage::disk('private')->path($originalPdfPath);
            $fullSignaturePath = $signatureImagePath ? Storage::disk('private')->path($signatureImagePath) : null;

            // Determine if this is a drawn or typed signature
            $isDrawnSignature = $signatureImagePath !== null;

            // Create FPDI instance (extends TCPDF)
            $pdf = new Fpdi();

            // Suppress header/footer
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);

            // Set margins
            $pdf->SetMargins(10, 10, 10);

            // Set source file and get page count
            $pageCount = $pdf->setSourceFile($fullPdfPath);

            // Import all pages from original PDF
            for ($i = 1; $i <= $pageCount; $i++) {
                // Import page
                $templateId = $pdf->importPage($i);
                
                // Add a new page
                $pdf->addPage();
                
                // Use template (adds original content)
                $pdf->useTemplate($templateId);

                // Only add signature to last page
                if ($i === $pageCount) {
                    if ($isDrawnSignature) {
                        $this->addDrawnSignatureToPage($pdf, $fullSignaturePath, $signerName);
                    } else {
                        $this->addTypedSignatureToPage($pdf, $signatureData, $signerName);
                    }
                }
            }

            // Save the signed PDF
            $signedDir = 'documents/signed-pdfs';
            if (!Storage::disk('private')->exists($signedDir)) {
                Storage::disk('private')->makeDirectory($signedDir);
            }

            $signedFileName = "DOC-{$documentId}_SIGNED_" . uniqid() . '.pdf';
            $signedPdfPath = "{$signedDir}/{$signedFileName}";
            $fullSignedPath = Storage::disk('private')->path($signedPdfPath);

            // Output to file
            $pdf->Output($fullSignedPath, 'F');

            if (!file_exists($fullSignedPath)) {
                \Log::error("Failed to save signed PDF to: {$fullSignedPath}");
                return null;
            }

            \Log::info("Signed PDF created: {$signedPdfPath}");
            return $signedPdfPath;

        } catch (\Exception $e) {
            \Log::error('TCPDF signing error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Add drawn signature image to the lower right of the current page
     */
    private function addDrawnSignatureToPage($pdf, $signaturePath, $signerName)
    {
        try {
            $now = Carbon::now('America/Chicago');  // Central Standard Time
            $dateFormatted = $now->format('Y-m-d');
            $timeFormatted = $now->format('H:i:s');

            // Get page dimensions
            $pageWidth = $pdf->GetPageWidth();
            $pageHeight = $pdf->GetPageHeight();

            // Signature position - lower right corner with margins
            $signatureWidth = 40;    // Width in mm
            $signatureHeight = 20;   // Height in mm (reduced for more compact display)
            $marginRight = 50;       // 50mm from right edge
            $marginBottom = 45;      // 45mm from bottom edge (more space for details below)

            $signatureX = $pageWidth - $signatureWidth - $marginRight;
            $signatureY = $pageHeight - $signatureHeight - $marginBottom;

            // Add signature image
            $pdf->Image(
                $signaturePath,
                $signatureX,
                $signatureY,
                $signatureWidth,
                $signatureHeight,
                'PNG'
            );

            // Add signature details BELOW the signature
            $pdf->SetFont('Helvetica', '', 8);
            $textX = $signatureX;
            $textY = $signatureY + $signatureHeight + 2;  // Below the image

            // Line 1: "Digitally Signed"
            $pdf->SetXY($textX, $textY);
            $pdf->Cell($signatureWidth, 3, 'Digitally Signed', 0, 1);

            // Line 2: "by: [Name]"
            $textY += 3;
            $pdf->SetXY($textX, $textY);
            $pdf->Cell($signatureWidth, 3, 'by: ' . substr($signerName, 0, 20), 0, 1);

            // Line 3: "Date: YYYY-MM-DD"
            $textY += 3;
            $pdf->SetXY($textX, $textY);
            $pdf->Cell($signatureWidth, 3, 'Date: ' . $dateFormatted, 0, 1);

            // Line 4: "HH:MM:SS"
            $textY += 3;
            $pdf->SetXY($textX, $textY);
            $pdf->Cell($signatureWidth, 3, $timeFormatted, 0, 1);

        } catch (\Exception $e) {
            \Log::warning('Error adding drawn signature to page: ' . $e->getMessage());
        }
    }

    /**
     * Add typed signature text to the lower right of the current page
     */
    private function addTypedSignatureToPage($pdf, $signatureText, $signerName)
    {
        try {
            $now = Carbon::now('America/Chicago');  // Central Standard Time
            $dateFormatted = $now->format('Y-m-d');
            $timeFormatted = $now->format('H:i:s');

            // Get page dimensions
            $pageWidth = $pdf->GetPageWidth();
            $pageHeight = $pdf->GetPageHeight();

            // Signature position - lower right corner with margins
            $marginRight = 30;       // 30mm from right edge (more left space for longer names)
            $marginBottom = 55;      // 55mm from bottom edge (enough space for signature + 4 lines)
            $cellWidth = 50;         // Width of text cell

            // Position at lower right, aligned to right edge
            $signatureX = $pageWidth - $marginRight - $cellWidth;
            $signatureY = $pageHeight - $marginBottom;

            // Add typed signature in cursive style (right-aligned)
            $pdf->SetFont('Courier', 'I', 24);
            $pdf->SetXY($signatureX, $signatureY);
            $pdf->Cell($cellWidth, 10, substr($signatureText, 0, 30), 0, 1, 'R');

            // Add signature details below typed text (left-aligned)
            $pdf->SetFont('Helvetica', '', 8);
            $textY = $signatureY + 10;

            // Line 1: "Digitally Signed"
            $pdf->SetXY($signatureX, $textY);
            $pdf->Cell($cellWidth, 3, 'Digitally Signed', 0, 1, 'L');

            // Line 2: "by: [Name]"
            $textY += 3;
            $pdf->SetXY($signatureX, $textY);
            $pdf->Cell($cellWidth, 3, 'by: ' . substr($signerName, 0, 20), 0, 1, 'L');

            // Line 3: "Date: YYYY-MM-DD"
            $textY += 3;
            $pdf->SetXY($signatureX, $textY);
            $pdf->Cell($cellWidth, 3, 'Date: ' . $dateFormatted, 0, 1, 'L');

            // Line 4: "HH:MM:SS"
            $textY += 3;
            $pdf->SetXY($signatureX, $textY);
            $pdf->Cell($cellWidth, 3, $timeFormatted, 0, 1, 'L');

        } catch (\Exception $e) {
            \Log::warning('Error adding typed signature to page: ' . $e->getMessage());
        }
    }
}
