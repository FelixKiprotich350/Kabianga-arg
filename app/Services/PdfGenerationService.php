<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PdfGenerationService
{
    private array $defaultOptions = [
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => false,
        'isRemoteEnabled' => false,
        'defaultFont' => 'DejaVu Sans',
        'dpi' => 96,
        'enable_font_subsetting' => true,
        'chroot' => null
    ];
 

    public function streamPdf($pdfContent, string $filename): \Illuminate\Http\Response
    {
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    public function downloadPdf($pdfContent, string $filename): \Illuminate\Http\Response
    {
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate'
        ]);
    }

    public function savePdf($pdfContent, string $path): bool
    {
        try {
            Storage::put($path, $pdfContent);
            return true;
        } catch (\Exception $e) {
            Log::error('PDF Save Error', ['path' => $path, 'error' => $e->getMessage()]);
            return false;
        }
    }

    private function generateFilename(string $code, string $prefix = 'Document'): string
    {
        $sanitizedCode = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $code);
        return $prefix . '-' . $sanitizedCode . '-' . date('Y-m-d') . '.pdf';
    }
}