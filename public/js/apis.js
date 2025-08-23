/**
 * Legacy API functions - maintained for backward compatibility
 * New development should use the APIService class in api-service.js
 */

// Legacy dashboard functions
function loadDashboard() {
    return PageLoaders.loadDashboardData();
}

// Legacy proposal functions
function loadAllProposals() {
    return PageLoaders.loadProposalsData('all');
}

function loadMyProposals() {
    return PageLoaders.loadProposalsData('my');
}

// Legacy project functions
function loadAllProjects() {
    return PageLoaders.loadProjectsData('all');
}

function loadMyProjects() {
    return PageLoaders.loadProjectsData('my');
}

// Legacy user functions
function loadUsers() {
    return PageLoaders.loadUsersData();
}

// Legacy search function
function searchData(query, type) {
    return PageLoaders.performSearch(query, type);
}

// Utility function for CSRF token
function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

// Generic AJAX helper
function makeAjaxRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCSRFToken(),
            'Content-Type': 'application/json'
        }
    };
    
    return fetch(url, { ...defaultOptions, ...options })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('AJAX Error:', error);
            throw error;
        });
}

