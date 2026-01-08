<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PdfSigningService
{
    /**
     * Sign a PDF document by overlaying signature image or typed text
     */
    public function signPdf($originalFilePath, $signatureData, $signatureType, $signerName, $documentId)
    {
        try {
            // Verify the original PDF exists
            if (!Storage::disk('private')->exists($originalFilePath)) {
                \Log::error("Original file not found: {$originalFilePath}");
                return null;
            }

            $extension = pathinfo($originalFilePath, PATHINFO_EXTENSION);

            // Only process PDF files
            if (strtolower($extension) !== 'pdf') {
                \Log::info("Skipping non-PDF file for PDF signing: {$extension}");
                return null;
            }

            // For both drawn and typed signatures, use the embedder to create a signed PDF
            $embedder = new PdfSignatureEmbedder();
            return $embedder->createSignedPdf($originalFilePath, $signatureData, $signerName, $documentId);

        } catch (\Exception $e) {
            \Log::error('PDF signing error: ' . $e->getMessage());
            return null;
        }
    }
}
