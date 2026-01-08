<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * PdfSignatureEmbedder - Embed signatures into PDFs using TCPDF
 * 
 * Delegates to TcpdfSigningService to handle TCPDF integration.
 */
class PdfSignatureEmbedder
{
    /**
     * Create a signed PDF by overlaying signature
     */
    public function createSignedPdf($originalPdfPath, $signatureData, $signerName, $documentId)
    {
        try {
            // Verify original PDF exists
            if (!Storage::disk('private')->exists($originalPdfPath)) {
                \Log::error("Original PDF not found: {$originalPdfPath}");
                return null;
            }

            // Extract and save signature image (for drawn signatures only)
            $signatureImagePath = null;
            if (strpos($signatureData, 'data:image') === 0) {
                $signatureImagePath = $this->extractAndSaveSignatureImage($signatureData, $documentId);
                if (!$signatureImagePath) {
                    \Log::error("Failed to extract signature image for document {$documentId}");
                    return null;
                }
                \Log::info("Signature image saved: {$signatureImagePath}");
            }
            // For typed signatures, signatureData is just text - no need to extract image

            // Use TCPDF service to sign the PDF
            $tcpdfService = new TcpdfSigningService();
            $signedPdfPath = $tcpdfService->signPdf(
                $originalPdfPath,
                $signatureImagePath,
                $signatureData,
                $signerName,
                $documentId
            );

            if (!$signedPdfPath) {
                \Log::error("TCPDF signing failed for document {$documentId}");
                return null;
            }

            \Log::info("PDF signed successfully: {$signedPdfPath}");
            return $signedPdfPath;

        } catch (\Exception $e) {
            \Log::error('PdfSignatureEmbedder error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract and save signature image from base64
     */
    private function extractAndSaveSignatureImage($base64Data, $documentId)
    {
        try {
            // Remove data URI prefix
            if (strpos($base64Data, 'data:image/png;base64,') === 0) {
                $base64Data = substr($base64Data, strlen('data:image/png;base64,'));
            }

            // Decode base64
            $imageData = base64_decode($base64Data, true);
            if ($imageData === false) {
                \Log::error("Failed to decode base64 signature data");
                return null;
            }

            // Save signature image
            $signatureDir = 'documents/signatures';
            if (!Storage::disk('private')->exists($signatureDir)) {
                Storage::disk('private')->makeDirectory($signatureDir);
            }

            $fileName = "{$signatureDir}/signature_{$documentId}_" . uniqid() . '.png';
            Storage::disk('private')->put($fileName, $imageData);

            if (!Storage::disk('private')->exists($fileName)) {
                \Log::error("Failed to save signature image to: {$fileName}");
                return null;
            }

            // Crop the signature to remove empty space
            $croppedFileName = $this->cropSignatureImage($fileName, $documentId);
            
            // Delete the original uncropped version if cropping succeeded
            if ($croppedFileName && $croppedFileName !== $fileName) {
                Storage::disk('private')->delete($fileName);
                return $croppedFileName;
            }

            return $fileName;
        } catch (\Exception $e) {
            \Log::error('Signature image extraction error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Crop signature image to remove empty/transparent space
     * Returns the path to the cropped image
     */
    private function cropSignatureImage($signaturePath, $documentId)
    {
        try {
            $fullPath = Storage::disk('private')->path($signaturePath);
            
            if (!file_exists($fullPath)) {
                return null;
            }

            // Load the image
            $image = imagecreatefrompng($fullPath);
            if (!$image) {
                \Log::warning("Could not load signature image for cropping: {$fullPath}");
                return null;
            }

            $width = imagesx($image);
            $height = imagesy($image);

            // Find bounding box of non-transparent pixels
            $minX = $width;
            $maxX = 0;
            $minY = $height;
            $maxY = 0;

            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $rgba = imagecolorat($image, $x, $y);
                    $alpha = ($rgba >> 24) & 0x7F;
                    
                    // If pixel is not fully transparent (alpha < 127 means not transparent)
                    if ($alpha < 127) {
                        $minX = min($minX, $x);
                        $maxX = max($maxX, $x);
                        $minY = min($minY, $y);
                        $maxY = max($maxY, $y);
                    }
                }
            }

            // If no non-transparent pixels found, return original
            if ($minX >= $maxX || $minY >= $maxY) {
                imagedestroy($image);
                return null;
            }

            // Minimal padding around signature (just 1 pixel to prevent edge issues)
            $padding = 1;
            $minX = max(0, $minX - $padding);
            $minY = max(0, $minY - $padding);
            $maxX = min($width - 1, $maxX + $padding);
            $maxY = min($height - 1, $maxY + $padding);

            $croppedWidth = $maxX - $minX + 1;
            $croppedHeight = $maxY - $minY + 1;

            // Create cropped image
            $croppedImage = imagecreatetruecolor($croppedWidth, $croppedHeight);
            
            // Preserve transparency
            imagealphablending($croppedImage, false);
            imagesavealpha($croppedImage, true);
            $transparent = imagecolorallocatealpha($croppedImage, 0, 0, 0, 127);
            imagefilledrectangle($croppedImage, 0, 0, $croppedWidth, $croppedHeight, $transparent);

            // Copy the cropped area
            imagecopy($croppedImage, $image, 0, 0, $minX, $minY, $croppedWidth, $croppedHeight);

            // Save cropped image
            $croppedDir = 'documents/signatures';
            $croppedFileName = "{$croppedDir}/signature_{$documentId}_cropped_" . uniqid() . '.png';
            $croppedFullPath = Storage::disk('private')->path($croppedFileName);
            
            imagepng($croppedImage, $croppedFullPath);
            imagedestroy($image);
            imagedestroy($croppedImage);

            if (file_exists($croppedFullPath)) {
                \Log::info("Signature cropped successfully: {$croppedFileName}");
                return $croppedFileName;
            }

            return null;

        } catch (\Exception $e) {
            \Log::warning('Error cropping signature image: ' . $e->getMessage());
            return null;
        }
    }
}
