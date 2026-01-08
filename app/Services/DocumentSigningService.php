<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocumentSigningService
{
    /**
     * Create a signed document (just PDF signing, no certificate)
     */
    public function createSignedDocument($originalFilePath, $signatureData, $signatureType, $signerName, $documentId)
    {
        try {
            // Get file extension
            $ext = strtolower(pathinfo($originalFilePath, PATHINFO_EXTENSION));

            // For PDF files, use PDF signing service
            if ($ext === 'pdf') {
                $pdfService = new PdfSigningService();
                return $pdfService->signPdf($originalFilePath, $signatureData, $signatureType, $signerName, $documentId);
            }

            // For non-PDF files, return the original path
            return $originalFilePath;

        } catch (\Exception $e) {
            \Log::error('Document signing error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Crop canvas to remove unused whitespace
     */
    private function cropCanvasImage($base64Data)
    {
        try {
            // Remove data URI prefix
            if (strpos($base64Data, 'data:image/png;base64,') === 0) {
                $base64Data = substr($base64Data, strlen('data:image/png;base64,'));
            }

            // Decode image
            $imageData = base64_decode($base64Data);
            $image = imagecreatefromstring($imageData);
            
            if (!$image) {
                return $base64Data; // Return original if cropping fails
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
                    $pixelColor = imagecolorat($image, $x, $y);
                    $alpha = ($pixelColor >> 24) & 0xFF;
                    
                    // If not transparent
                    if ($alpha < 127) {
                        $minX = min($minX, $x);
                        $maxX = max($maxX, $x);
                        $minY = min($minY, $y);
                        $maxY = max($maxY, $y);
                    }
                }
            }

            // If no non-transparent pixels found, return original
            if ($minX > $maxX || $minY > $maxY) {
                imagedestroy($image);
                return $base64Data;
            }

            // Add small padding
            $padding = 5;
            $cropX = max(0, $minX - $padding);
            $cropY = max(0, $minY - $padding);
            $cropWidth = min($width, $maxX - $cropX + $padding * 2);
            $cropHeight = min($height, $maxY - $cropY + $padding * 2);

            // Create cropped image
            $croppedImage = imagecreatetruecolor($cropWidth, $cropHeight);
            imagesavealpha($croppedImage, true);
            $transparent = imagecolorallocatealpha($croppedImage, 255, 255, 255, 127);
            imagefill($croppedImage, 0, 0, $transparent);
            imagecopy($croppedImage, $image, 0, 0, $cropX, $cropY, $cropWidth, $cropHeight);

            // Convert back to base64
            ob_start();
            imagepng($croppedImage);
            $croppedData = ob_get_clean();
            
            imagedestroy($image);
            imagedestroy($croppedImage);

            return 'data:image/png;base64,' . base64_encode($croppedData);
        } catch (\Exception $e) {
            \Log::error('Canvas crop error: ' . $e->getMessage());
            return $base64Data; // Return original on error
        }
    }

    /**
     * Create an HTML signature certificate
     */
    private function createSignatureCertificate($signatureData, $signatureType, $signerName, $documentId)
    {
        try {
            $certificateDir = 'documents/certificates';

            if (!Storage::disk('private')->exists($certificateDir)) {
                Storage::disk('private')->makeDirectory($certificateDir);
            }

            $safeSignerName = htmlspecialchars($signerName, ENT_QUOTES, 'UTF-8');
            $signedAt = now()->format('M d, Y \a\t h:i A');
            $isoTimestamp = now()->toIso8601String();
            
            $signatureImageHtml = '';

            // Handle drawn signature
            if ($signatureType === 'draw' && strpos($signatureData, 'data:image') === 0) {
                // Crop and save the signature image
                $croppedSignature = $this->cropCanvasImage($signatureData);
                $signatureImagePath = $this->saveSignatureImage($croppedSignature, $documentId);
                if ($signatureImagePath && Storage::disk('private')->exists($signatureImagePath)) {
                    $imageData = base64_encode(Storage::disk('private')->get($signatureImagePath));
                    $signatureImageHtml = "<img src='data:image/png;base64,{$imageData}' alt='Signature' style='max-height: 100px; width: auto;'>";
                }
            } else {
                // Typed signature - use a web-safe cursive font
                $signatureImageHtml = "<div style='font-family: Palatino Linotype, Palatino, Georgia, serif; font-size: 52px; color: #333; font-style: italic; margin: 0; padding: 0;'>{$safeSignerName}</div>";
            }

            $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document Signature - DOC-{$documentId}</title>
    <style>
        * { margin: 0; padding: 0; }
        html, body { margin: 0; padding: 20px; width: 100%; background: #f5f5f5; font-family: 'Segoe UI', Arial, sans-serif; }
        .certificate { background: white; border: 2px solid #2c3e50; padding: 40px; margin: 0 auto; border-radius: 8px; max-width: 900px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2c3e50; padding-bottom: 20px; }
        .header h1 { font-size: 28px; color: #2c3e50; margin: 10px 0 5px 0; }
        .header p { color: #7f8c8d; font-size: 13px; }
        .info-box { margin: 15px 0; padding: 12px; background: #ecf0f1; border-left: 4px solid #3498db; }
        .info-label { font-weight: 600; color: #2c3e50; font-size: 12px; }
        .info-value { color: #34495e; font-size: 13px; margin-top: 2px; }
        .signature-section { margin: 35px 0; text-align: center; }
        .sig-title { font-size: 12px; font-weight: 600; color: #2c3e50; text-transform: uppercase; margin-bottom: 15px; }
        .sig-display { 
            min-height: 80px; 
            border-bottom: 2px solid #333; 
            padding: 15px 0; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            margin-bottom: 30px;
        }
        .sig-display img { max-height: 100px; width: auto; }
        .sig-display div { width: 100%; word-wrap: break-word; }
        .details-section { margin: 30px 0; }
        .details-title { font-size: 12px; font-weight: 600; color: #2c3e50; text-transform: uppercase; margin-bottom: 15px; text-align: center; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .detail-item { text-align: left; }
        .detail-label { font-weight: 600; color: #2c3e50; font-size: 11px; text-transform: uppercase; }
        .detail-value { color: #34495e; font-size: 12px; margin-top: 3px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #ecf0f1; }
        .footer p { color: #7f8c8d; font-size: 10px; margin: 4px 0; }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div style="font-size: 36px; margin-bottom: 8px;">✓</div>
            <h1>Document Signed</h1>
            <p>Signature Record</p>
        </div>

        <div class="info-box">
            <div class="info-label">Document ID</div>
            <div class="info-value">DOC-{$documentId}</div>
        </div>
        
        <div class="info-box">
            <div class="info-label">Signed By</div>
            <div class="info-value">{$safeSignerName}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Date & Time</div>
            <div class="info-value">{$signedAt}</div>
        </div>

        <div class="signature-section">
            <div class="sig-title">Signature</div>
            <div class="sig-display">
                {$signatureImageHtml}
            </div>
        </div>

        <div class="details-section">
            <div class="details-title">Digitally Signed Details</div>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Signed By</div>
                    <div class="detail-value">{$safeSignerName}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date & Time</div>
                    <div class="detail-value">{$signedAt}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Document ID</div>
                    <div class="detail-value">DOC-{$documentId}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Timestamp</div>
                    <div class="detail-value" style="font-family: monospace; font-size: 10px;">{$isoTimestamp}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>This certifies that the document has been digitally signed.</strong></p>
            <p>Signer: {$safeSignerName} | Signed on: {$signedAt}</p>
            <p style="margin-top: 8px; color: #95a5a6; font-family: monospace; font-size: 9px;">{$isoTimestamp}</p>
        </div>
    </div>
</body>
</html>
HTML;

            $certificatePath = "{$certificateDir}/doc_{$documentId}_certificate_" . uniqid() . '.html';
            Storage::disk('private')->put($certificatePath, $html);

            \Log::info("Certificate created at: {$certificatePath}");
            return $certificatePath;

        } catch (\Exception $e) {
            \Log::error('Certificate creation error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Save signature image from canvas
     */
    private function saveSignatureImage($base64Data, $documentId)
    {
        try {
            // Remove data URI prefix if present
            if (strpos($base64Data, 'data:image/png;base64,') === 0) {
                $base64Data = substr($base64Data, strlen('data:image/png;base64,'));
            }

            // Decode base64
            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                return null;
            }

            // Save signature image
            $signatureDir = 'documents/signatures';
            if (!Storage::disk('private')->exists($signatureDir)) {
                Storage::disk('private')->makeDirectory($signatureDir);
            }

            $fileName = "{$signatureDir}/signature_{$documentId}_" . uniqid() . '.png';
            Storage::disk('private')->put($fileName, $imageData);

            return $fileName;
        } catch (\Exception $e) {
            \Log::error('Signature image save error: ' . $e->getMessage());
            return null;
        }
    }
}
