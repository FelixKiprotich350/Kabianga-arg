# API Integration Guide - Kabianga ARG Portal

This guide explains how to use the new API-driven data fetching system implemented for all pages in the Kabianga Annual Research Grants Portal.

## Overview

The system consists of four main JavaScript files that work together to provide seamless API data fetching:

1. **api-service.js** - Centralized API service class
2. **page-loaders.js** - Page-specific data loading functions
3. **data-renderers.js** - Functions to render fetched data into UI
4. **modern-app.js** - Enhanced with utility functions

## File Structure

```
public/js/
├── api-service.js      # APIService class with all API endpoints
├── page-loaders.js     # Page-specific data loaders
├── data-renderers.js   # UI rendering functions
├── modern-app.js       # Enhanced app utilities
└── apis.js            # Legacy functions (backward compatibility)
```

## Usage Examples

### 1. Dashboard Page

```javascript
// Automatic loading on page load
document.addEventListener('DOMContentLoaded', function() {
    PageLoaders.loadDashboardData();
});

// Manual refresh
function refreshDashboard() {
    PageLoaders.loadDashboardData();
}
```

### 2. Proposals Page

```javascript
// Load all proposals
PageLoaders.loadProposalsData('all');

// Load user's proposals
PageLoaders.loadProposalsData('my');

// Load specific proposal details
PageLoaders.loadProposalDetails(proposalId);
```

### 3. Projects Page

```javascript
// Load all projects
PageLoaders.loadProjectsData('all');

// Load active projects only
PageLoaders.loadProjectsData('active');

// Load user's projects
PageLoaders.loadProjectsData('my');
```

### 4. Search Functionality

```javascript
// Search across different data types
const results = await PageLoaders.performSearch('query', 'proposals');
const users = await PageLoaders.performSearch('john', 'users');
```

## API Service Usage

### Direct API Calls

```javascript
// Get all proposals
const proposals = await API.getAllProposals();

// Get specific proposal
const proposal = await API.getProposal(123);

// Create new proposal
const newProposal = await API.createProposal({
    title: 'Research Title',
    abstract: 'Research abstract...',
    // ... other fields
});

// Update proposal
await API.updateProposal(123, updatedData);
```

### Error Handling

```javascript
try {
    const data = await API.getAllProposals();
    DataRenderers.renderProposalsList(data);
} catch (error) {
    ARGPortal.showError('Failed to load proposals');
    console.error('Error:', error);
}
```

## Available API Endpoints

### Dashboard APIs
- `getDashboardStats()` - Get dashboard statistics
- `getDashboardChart()` - Get chart data
- `getRecentActivity()` - Get recent activities

### User Management APIs
- `getAllUsers(search)` - Get all users with optional search
- `getUser(id)` - Get specific user
- `createUser(userData)` - Create new user
- `updateUser(id, userData)` - Update user

### Proposals APIs
- `getAllProposals(search)` - Get all proposals
- `getMyProposals()` - Get current user's proposals
- `getProposal(id)` - Get specific proposal
- `createProposal(data)` - Create new proposal
- `updateProposal(id, data)` - Update proposal
- `submitProposal(id)` - Submit proposal for review
- `approveRejectProposal(id, action, comments)` - Approve/reject proposal

### Projects APIs
- `getAllProjects(search)` - Get all projects
- `getMyProjects()` - Get user's projects
- `getActiveProjects()` - Get active projects only
- `getProject(id)` - Get specific project
- `submitProgress(id, data)` - Submit project progress
- `getProjectProgress(id)` - Get project progress

### Schools & Departments APIs
- `getAllSchools(search)` - Get all schools
- `getAllDepartments(search)` - Get all departments
- `createSchool(data)` - Create new school
- `createDepartment(data)` - Create new department

### Grants APIs
- `getAllGrants(search)` - Get all grants
- `createGrant(data)` - Create new grant
- `updateGrant(id, data)` - Update grant

## Page Templates

### Basic Page Structure

```html
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page content with loading states -->
    <div id="page-content">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading data...</p>
        </div>
    </div>
    
    <!-- Data containers -->
    <div id="data-list"></div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load page data
    PageLoaders.loadPageData();
});
</script>
@endpush
```

### Search Implementation

```html
<!-- Search input -->
<div class="input-group mb-3">
    <input type="text" id="search-input" class="form-control" placeholder="Search...">
    <button class="btn btn-outline-secondary" onclick="performSearch()">
        <i class="bi bi-search"></i>
    </button>
</div>

<script>
// Debounced search
const debouncedSearch = ARGPortal.debounce(async function() {
    const query = document.getElementById('search-input').value;
    const results = await PageLoaders.performSearch(query, 'proposals');
    DataRenderers.renderProposalsList(results);
}, 300);

document.getElementById('search-input').addEventListener('input', debouncedSearch);
</script>
```

## Utility Functions

### ARGPortal Utilities

```javascript
// Show loading state
ARGPortal.showLoading(element);

// Show notifications
ARGPortal.showSuccess('Operation completed');
ARGPortal.showError('Something went wrong');
ARGPortal.showToast('Custom message', 'info');

// Format numbers
ARGPortal.formatNumber(1234567); // "1,234,567"

// Debounce function calls
const debouncedFn = ARGPortal.debounce(myFunction, 300);
```

## Best Practices

### 1. Loading States
Always show loading indicators while fetching data:

```javascript
ARGPortal.showLoading(document.getElementById('content'));
try {
    const data = await API.getData();
    renderData(data);
} catch (error) {
    ARGPortal.showError('Failed to load data');
}
```

### 2. Error Handling
Implement proper error handling for all API calls:

```javascript
try {
    const result = await API.someOperation();
    ARGPortal.showSuccess('Operation successful');
} catch (error) {
    ARGPortal.showError(error.message || 'Operation failed');
    console.error('Error:', error);
}
```

### 3. Search Optimization
Use debouncing for search inputs to avoid excessive API calls:

```javascript
const debouncedSearch = ARGPortal.debounce(searchFunction, 300);
searchInput.addEventListener('input', debouncedSearch);
```

### 4. Data Caching
Consider caching frequently accessed data:

```javascript
let cachedData = null;
let cacheTime = null;

async function getCachedData() {
    const now = Date.now();
    if (cachedData && cacheTime && (now - cacheTime < 300000)) { // 5 minutes
        return cachedData;
    }
    
    cachedData = await API.getData();
    cacheTime = now;
    return cachedData;
}
```

## Migration from Old System

### Before (Old Way)
```javascript
$.ajax({
    url: '/api/proposals',
    method: 'GET',
    success: function(data) {
        // Handle success
    },
    error: function() {
        // Handle error
    }
});
```

### After (New Way)
```javascript
try {
    const proposals = await API.getAllProposals();
    DataRenderers.renderProposalsList(proposals);
} catch (error) {
    ARGPortal.showError('Failed to load proposals');
}
```

## Troubleshooting

### Common Issues

1. **CSRF Token Missing**
   - Ensure `<meta name="csrf-token" content="{{ csrf_token() }}">` is in your layout
   - The API service automatically includes the CSRF token

2. **API Endpoint Not Found**
   - Check that the route exists in `routes/api.php`
   - Verify the endpoint URL in `api-service.js`

3. **Data Not Rendering**
   - Check browser console for JavaScript errors
   - Verify the data structure matches what the renderer expects

4. **Loading States Not Working**
   - Ensure the target element exists in the DOM
   - Check that the element ID matches what's used in the loader

### Debug Mode

Enable debug mode by adding this to your page:

```javascript
window.DEBUG_API = true;

// This will log all API calls to console
```

## Contributing

When adding new API endpoints:

1. Add the route to `routes/api.php`
2. Add the method to `APIService` class in `api-service.js`
3. Create a loader function in `page-loaders.js`
4. Create a renderer function in `data-renderers.js`
5. Update this documentation

## Support

For issues or questions about the API integration system, please:

1. Check the browser console for errors
2. Verify API endpoints are working via browser network tab
3. Review this documentation
4. Contact the development team