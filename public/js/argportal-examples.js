/**
 * ARGPortal Notification Examples
 * This file demonstrates how to use ARGPortal notifications across the application
 */

// Example usage patterns for ARGPortal notifications

// 1. Basic notifications
function showBasicNotifications() {
    ARGPortal.showSuccess('Operation completed successfully!');
    ARGPortal.showError('An error occurred while processing your request.');
    ARGPortal.showWarning('Please review your input before proceeding.');
    ARGPortal.showInfo('New features are now available in the system.');
}

// 2. Proposal-specific notifications
function showProposalNotifications() {
    // When a proposal is submitted
    ARGPortal.proposal.submitted('Advanced AI Research Project');
    
    // When a proposal is saved
    ARGPortal.proposal.saved();
    
    // When a proposal is approved
    ARGPortal.proposal.approved('Advanced AI Research Project');
    
    // When a proposal is rejected
    ARGPortal.proposal.rejected('Advanced AI Research Project', 'Budget exceeds available funds');
    
    // When changes are requested
    ARGPortal.proposal.changesRequested('Advanced AI Research Project');
}

// 3. User-specific notifications
function showUserNotifications() {
    // Login notifications
    ARGPortal.user.loggedIn('Dr. John Smith');
    ARGPortal.user.loggedOut();
    
    // Profile updates
    ARGPortal.user.profileUpdated();
    ARGPortal.user.passwordChanged();
}

// 4. Research-specific notifications
function showResearchNotifications() {
    // Project creation
    ARGPortal.research.projectCreated('Machine Learning in Healthcare');
    
    // Progress updates
    ARGPortal.research.progressUpdated();
    
    // Funding notifications
    ARGPortal.research.fundingApproved('KES 500,000');
    
    // Deadline reminders
    ARGPortal.research.deadlineReminder('Final Report Submission', 7);
}

// 5. System notifications
function showSystemNotifications() {
    // Maintenance notifications
    ARGPortal.system.maintenance('System will be down for maintenance on Sunday 2-4 AM');
    
    // System updates
    ARGPortal.system.update('New features have been added to the proposal system');
    
    // System errors
    ARGPortal.system.error('Database connection temporarily unavailable');
}

// 6. AJAX response handling
function handleAjaxResponse(response) {
    if (response.success) {
        if (response.type === 'success') {
            ARGPortal.showSuccess(response.message);
        } else if (response.type === 'warning') {
            ARGPortal.showWarning(response.message);
        } else {
            ARGPortal.showInfo(response.message);
        }
    } else {
        ARGPortal.showError(response.message || 'An error occurred');
    }
}

// 7. Form validation notifications
function showFormValidationNotifications() {
    // Success
    ARGPortal.showSuccess('Form submitted successfully!');
    
    // Validation errors
    ARGPortal.showError('Please fill in all required fields');
    ARGPortal.showWarning('Some fields contain invalid data');
    
    // Information
    ARGPortal.showInfo('All fields marked with * are required');
}

// 8. File upload notifications
function showFileUploadNotifications() {
    // Upload success
    ARGPortal.showSuccess('File uploaded successfully');
    
    // Upload errors
    ARGPortal.showError('File size exceeds maximum limit (5MB)');
    ARGPortal.showError('Invalid file type. Only PDF files are allowed');
    
    // Upload progress (info)
    ARGPortal.showInfo('Uploading file... Please wait');
}

// 9. Data loading notifications
function showDataLoadingNotifications() {
    // Loading success
    ARGPortal.showSuccess('Data loaded successfully');
    
    // Loading errors
    ARGPortal.showError('Failed to load data. Please try again');
    
    // No data
    ARGPortal.showInfo('No data available for the selected criteria');
}

// 10. Permission-based notifications
function showPermissionNotifications() {
    // Access denied
    ARGPortal.showError('You do not have permission to perform this action');
    
    // Permission granted
    ARGPortal.showSuccess('Access granted. You can now proceed');
    
    // Permission warning
    ARGPortal.showWarning('Limited access: Some features may not be available');
}

// Example of integrating with existing toast system
function integrateWithExistingToast() {
    // If you have an existing showtoastmessage function, ARGPortal will use it automatically
    // Otherwise, it will create its own notification system
    
    // You can also manually trigger the existing toast system
    if (typeof showtoastmessage === 'function') {
        showtoastmessage({
            message: 'Using existing toast system',
            type: 'success'
        });
    }
}

// Export functions for use in other files
window.ARGPortalExamples = {
    showBasicNotifications,
    showProposalNotifications,
    showUserNotifications,
    showResearchNotifications,
    showSystemNotifications,
    handleAjaxResponse,
    showFormValidationNotifications,
    showFileUploadNotifications,
    showDataLoadingNotifications,
    showPermissionNotifications,
    integrateWithExistingToast
};