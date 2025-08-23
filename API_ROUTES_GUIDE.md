# API Routes Implementation Guide

## Overview
The Kabianga ARG system has been refactored to properly separate concerns:
- **Web routes**: Serve pages only (GET requests for views)
- **API routes**: Handle all data operations (POST, PUT, PATCH, DELETE)

## Route Structure

### Web Routes (`/routes/web.php`)
Web routes now only serve pages and handle authentication flows:

```php
// Page serving routes only
Route::get('/proposals', [ProposalsController::class, 'index']);
Route::get('/proposals/newproposal', [ProposalsController::class, 'modernNewProposal']);
Route::get('/proposals/view/{id}', [ProposalsController::class, 'getsingleproposalpage']);
Route::get('/proposals/edit/{id}', [ProposalsController::class, 'geteditsingleproposalpage']);
```

### API Routes (`/routes/api.php`)
All data operations are handled through API routes with proper REST structure:

```php
// Proposals Management
Route::prefix('proposals')->group(function () {
    Route::get('/', [ProposalsController::class, 'fetchallproposals']);
    Route::post('/', [ProposalsController::class, 'postnewproposal']);
    Route::put('/{id}/basic', [ProposalsController::class, 'updatebasicdetails']);
    Route::put('/{id}/research', [ProposalsController::class, 'updateresearchdetails']);
    Route::post('/{id}/submit', [ProposalsController::class, 'submitproposal']);
    // ... more routes
});
```

## Controller Changes

### Before (Web Route Response)
```php
public function updatebasicdetails(Request $request, $id) {
    // ... validation and processing
    return redirect()->route('pages.proposals.editproposal', ['id' => $proposal->proposalid])
        ->with('success', 'Basic Details Saved Successfully!!');
}
```

### After (API Response)
```php
public function updatebasicdetails(Request $request, $id) {
    // ... validation and processing
    return response()->json([
        'success' => true,
        'message' => 'Basic Details Saved Successfully!!',
        'proposal_id' => $proposal->proposalid
    ], 200);
}
```

## Frontend Integration

### JavaScript API Client
Use the provided `ApiClient` class in `/public/js/api-client.js`:

```javascript
// Create a new proposal
const result = await window.apiClient.createProposal(formData);

// Update proposal basics
const result = await window.apiClient.updateProposalBasics(id, data);

// Get user's proposals
const proposals = await window.apiClient.getMyProposals();
```

### Form Handling
Replace form submissions to web routes with API calls:

```javascript
// Before: Form submits to web route
<form action="/proposals/post" method="POST">

// After: Form handled by JavaScript
<form id="proposal-form">
// JavaScript handles submission via API
```

## API Endpoints

### Proposals
- `GET /api/v1/proposals` - Get all proposals
- `POST /api/v1/proposals` - Create new proposal
- `GET /api/v1/proposals/my` - Get user's proposals
- `PUT /api/v1/proposals/{id}/basic` - Update basic details
- `PUT /api/v1/proposals/{id}/research` - Update research details
- `POST /api/v1/proposals/{id}/submit` - Submit proposal

### Users
- `GET /api/v1/users` - Get all users
- `POST /api/v1/users` - Create user
- `PUT /api/v1/users/{id}` - Update user

### Schools
- `GET /api/v1/schools` - Get all schools
- `POST /api/v1/schools` - Create school
- `PUT /api/v1/schools/{id}` - Update school

### Departments
- `GET /api/v1/departments` - Get all departments
- `POST /api/v1/departments` - Create department
- `PUT /api/v1/departments/{id}` - Update department

### Grants
- `GET /api/v1/grants` - Get all grants
- `POST /api/v1/grants` - Create grant
- `PUT /api/v1/grants/{id}` - Update grant

## Response Format

All API responses follow a consistent format:

### Success Response
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { /* response data */ }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": { /* validation errors if applicable */ }
}
```

## Migration Steps

1. **Update Forms**: Change form actions from web routes to use JavaScript API calls
2. **Update JavaScript**: Replace direct form submissions with API client calls
3. **Handle Responses**: Update success/error handling to work with JSON responses
4. **Update Navigation**: Use JavaScript to handle redirects after successful API calls

## Benefits

1. **Clear Separation**: Pages and data operations are clearly separated
2. **API First**: Frontend can be easily replaced or mobile apps can use same API
3. **Consistent Responses**: All data operations return JSON in consistent format
4. **Better Error Handling**: Structured error responses for better UX
5. **Scalability**: API can be versioned and extended independently

## Next Steps

1. Update all frontend forms to use the API client
2. Add proper error handling and loading states
3. Implement API authentication tokens if needed
4. Add API rate limiting and validation
5. Create API documentation for external consumers