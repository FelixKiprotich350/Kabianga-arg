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

    public function generateProposalPdf($proposal, array $options = []): array
    {
        try {
            $pdfOptions = array_merge($this->defaultOptions, $options);
            
            $pdf = Pdf::loadView('pages.proposals.printproposal', compact('proposal'))
                ->setPaper($options['paper'] ?? 'A4', $options['orientation'] ?? 'portrait')
                ->setOptions($pdfOptions);
            
            $filename = $this->generateFilename($proposal->proposalcode, 'Research-Proposal');
            
            return [
                'success' => true,
                'pdf' => $pdf,
                'filename' => $filename,
                'content' => $pdf->output()
            ];
            
        } catch (\Exception $e) {
            Log::error('PDF Generation Service Error', [
                'proposal_code' => $proposal->proposalcode ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

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