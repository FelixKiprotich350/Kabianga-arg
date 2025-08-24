# Laravel Snappy PDF Setup Guide

## Overview
Laravel Snappy has been successfully integrated into the Kabianga ARG system to provide high-quality PDF generation using wkhtmltopdf.

## Installation Summary
1. ✅ Installed `barryvdh/laravel-snappy` package
2. ✅ Published configuration file to `config/snappy.php`
3. ✅ Installed wkhtmltopdf binary
4. ✅ Configured environment variables
5. ✅ Added PDF generation methods to ProposalsController

## Configuration

### Environment Variables (.env)
```env
WKHTML_PDF_BINARY=/usr/bin/wkhtmltopdf
WKHTML_IMG_BINARY=/usr/bin/wkhtmltoimage
```

### Snappy Configuration (config/snappy.php)
The configuration includes optimized settings for:
- A4 page size
- Portrait orientation
- 10mm margins
- UTF-8 encoding
- Local file access enabled
- JavaScript delay for dynamic content

## Usage Examples

### Basic PDF Generation
```php
use Barryvdh\Snappy\Facades\SnappyPdf;

public function generatePDF()
{
    $html = '<html><body><h1>Hello World</h1></body></html>';
    $pdf = SnappyPdf::loadHTML($html);
    
    return response($pdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="document.pdf"'
    ]);
}
```

### PDF from Blade View
```php
public function generateFromView($id)
{
    $data = Model::findOrFail($id);
    $html = view('pdf.template', compact('data'))->render();
    $pdf = SnappyPdf::loadHTML($html);
    
    return $pdf->download('document.pdf');
}
```

### Custom Options
```php
$pdf = SnappyPdf::loadHTML($html)
    ->setOption('page-size', 'A4')
    ->setOption('orientation', 'Landscape')
    ->setOption('margin-top', '20mm');
```

## Available Routes

### Proposal PDF Generation
- **PDF Generation**: `GET /api/v1/proposals/{id}/pdf` (now uses Snappy)
- **Test Snappy**: `GET /api/v1/proposals/test-snappy`

## Implementation in Kabianga ARG

### ProposalsController Methods
1. `printpdf($id)` - Now uses Snappy (replaced DomPDF)
2. `testSnappy()` - Test method for verification

### Frontend Integration
The proposal view page now uses Snappy for PDF generation:
- Single "Download PDF" button using Snappy

## Advantages of Snappy over DomPDF

1. **Better CSS Support**: More accurate rendering of complex CSS
2. **JavaScript Support**: Can execute JavaScript before PDF generation
3. **Image Handling**: Better support for images and graphics
4. **Font Support**: Better font rendering and support
5. **Performance**: Generally faster for complex documents

## Troubleshooting

### Common Issues
1. **Binary not found**: Ensure wkhtmltopdf is installed and path is correct
2. **Permission errors**: Check file permissions for wkhtmltopdf binary
3. **Memory issues**: Increase PHP memory limit for large documents
4. **CSS not loading**: Use absolute URLs for CSS files

### Testing
Use the test route to verify Snappy is working:
```
GET /api/v1/proposals/test-snappy
```

### Debugging
Enable debug mode in .env to see detailed error messages:
```env
APP_DEBUG=true
```

## Best Practices

1. **Error Handling**: Always wrap PDF generation in try-catch blocks
2. **Memory Management**: Use pagination for large datasets
3. **Caching**: Consider caching generated PDFs for frequently accessed documents
4. **Optimization**: Use specific field selection in Eloquent queries
5. **Testing**: Test PDF generation with various data scenarios

## Security Considerations

1. **File Access**: `enable-local-file-access` is enabled - ensure proper validation
2. **Input Sanitization**: Sanitize HTML content before PDF generation
3. **Access Control**: Implement proper authorization for PDF endpoints
4. **Resource Limits**: Set appropriate timeouts and memory limits

## Maintenance

### Updating wkhtmltopdf
```bash
# Check current version
wkhtmltopdf --version

# Update via package manager (if installed via apt/yum)
sudo apt update && sudo apt upgrade wkhtmltopdf
```

### Monitoring
Monitor PDF generation performance and errors through Laravel logs:
```bash
tail -f storage/logs/laravel.log | grep "PDF Generation"
```