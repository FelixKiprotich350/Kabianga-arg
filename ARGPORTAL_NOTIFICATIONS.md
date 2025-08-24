# ARGPortal Notification System

## Overview

The ARGPortal notification system provides a consistent and user-friendly way to display notifications across the Kabianga Annual Research Grants Portal. It integrates with the existing toast notification system and provides specialized methods for different types of notifications.

## Features

- **Consistent API**: Unified interface for all notification types
- **Multiple Notification Types**: Success, Error, Warning, and Info notifications
- **Specialized Methods**: Context-specific notifications for proposals, users, research, and system messages
- **Fallback Support**: Automatically integrates with existing toast systems or creates its own
- **Customizable Duration**: Different display durations for different notification types
- **Responsive Design**: Works across all device sizes

## Installation

The ARGPortal notification system is automatically included in the master layout:

```html
<script src="{{ asset('js/argportal-notifications.js') }}"></script>
```

## Basic Usage

### Simple Notifications

```javascript
// Success notification (3 seconds)
ARGPortal.showSuccess('Operation completed successfully!');

// Error notification (5 seconds)
ARGPortal.showError('An error occurred while processing your request.');

// Warning notification (4 seconds)
ARGPortal.showWarning('Please review your input before proceeding.');

// Info notification (3 seconds)
ARGPortal.showInfo('New features are now available.');
```

### Generic Notification Method

```javascript
// Custom notification with specific type and duration
ARGPortal.notify('Custom message', 'success', 2000);
```

## Specialized Notifications

### Proposal Notifications

```javascript
// Proposal submitted
ARGPortal.proposal.submitted('Research Project Title');

// Proposal saved
ARGPortal.proposal.saved();

// Proposal approved
ARGPortal.proposal.approved('Research Project Title');

// Proposal rejected
ARGPortal.proposal.rejected('Research Project Title', 'Optional reason');

// Changes requested
ARGPortal.proposal.changesRequested('Research Project Title');
```

### User Notifications

```javascript
// User login
ARGPortal.user.loggedIn('Dr. John Smith');

// User logout
ARGPortal.user.loggedOut();

// Profile updated
ARGPortal.user.profileUpdated();

// Password changed
ARGPortal.user.passwordChanged();
```

### Research Notifications

```javascript
// Project created
ARGPortal.research.projectCreated('Machine Learning in Healthcare');

// Progress updated
ARGPortal.research.progressUpdated();

// Funding approved
ARGPortal.research.fundingApproved('KES 500,000');

// Deadline reminder
ARGPortal.research.deadlineReminder('Final Report Submission', 7);
```

### System Notifications

```javascript
// Maintenance notification (10 seconds)
ARGPortal.system.maintenance('System will be down for maintenance on Sunday 2-4 AM');

// System update
ARGPortal.system.update('New features have been added');

// System error
ARGPortal.system.error('Database connection temporarily unavailable');
```

## Integration with AJAX Responses

### Standard Response Handling

```javascript
fetch('/api/endpoint')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            ARGPortal.showSuccess(data.message);
        } else {
            ARGPortal.showError(data.message);
        }
    })
    .catch(error => {
        ARGPortal.showError('Network error occurred');
    });
```

### Controller Response Format

Ensure your Laravel controllers return responses in this format:

```php
// Success response
return response()->json([
    'success' => true,
    'message' => 'Operation completed successfully',
    'type' => 'success'
]);

// Error response
return response()->json([
    'success' => false,
    'message' => 'An error occurred',
    'type' => 'error'
], 400);
```

## Blade Template Integration

### Login Page Example

```html
<script>
    @if(session('login_success'))
        ARGPortal.user.loggedIn('{{ auth()->user()->name }}');
    @endif
    
    @if($errors->has('email'))
        ARGPortal.showError('{{ $errors->first('email') }}');
    @endif
</script>
```

### Dashboard Example

```html
<script>
    $(document).ready(function() {
        @if(session('login_success'))
            ARGPortal.user.loggedIn('{{ auth()->user()->name }}');
        @endif
    });
</script>
```

## Form Validation Integration

### Client-side Validation

```javascript
function validateForm() {
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        ARGPortal.showError('Please fill in all required fields');
        return false;
    }
    
    return true;
}
```

### Server-side Validation Response

```php
// In your controller
if ($validator->fails()) {
    return response()->json([
        'success' => false,
        'message' => 'Please fill in all required fields',
        'type' => 'error',
        'errors' => $validator->errors()
    ], 422);
}
```

## File Upload Integration

```javascript
function handleFileUpload(file) {
    // Validate file size
    if (file.size > 5 * 1024 * 1024) { // 5MB
        ARGPortal.showError('File size exceeds maximum limit (5MB)');
        return;
    }
    
    // Validate file type
    if (!file.type.includes('pdf')) {
        ARGPortal.showError('Invalid file type. Only PDF files are allowed');
        return;
    }
    
    // Show upload progress
    ARGPortal.showInfo('Uploading file... Please wait');
    
    // Perform upload
    uploadFile(file)
        .then(() => {
            ARGPortal.showSuccess('File uploaded successfully');
        })
        .catch(() => {
            ARGPortal.showError('Failed to upload file. Please try again');
        });
}
```

## Customization

### Custom Notification Duration

```javascript
// Custom duration (in milliseconds)
ARGPortal.showSuccess('Message', 5000); // 5 seconds
ARGPortal.showError('Error message', 10000); // 10 seconds
```

### Custom Styling

The notification system uses Bootstrap classes by default. You can customize the appearance by adding CSS:

```css
#argportal-notifications .alert {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

#argportal-notifications .alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}
```

## Best Practices

### 1. Use Appropriate Notification Types

- **Success**: For completed operations (save, submit, approve)
- **Error**: For failures and validation errors
- **Warning**: For important information that requires attention
- **Info**: For general information and tips

### 2. Keep Messages Clear and Concise

```javascript
// Good
ARGPortal.showSuccess('Proposal saved successfully');

// Avoid
ARGPortal.showSuccess('The proposal has been successfully saved to the database and you can now proceed to the next step');
```

### 3. Use Specialized Methods When Available

```javascript
// Preferred
ARGPortal.proposal.submitted('Research Title');

// Instead of
ARGPortal.showSuccess('Proposal "Research Title" has been submitted successfully');
```

### 4. Handle Network Errors Gracefully

```javascript
fetch('/api/endpoint')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        ARGPortal.showSuccess(data.message);
    })
    .catch(error => {
        ARGPortal.showError('Unable to connect to server. Please check your internet connection.');
    });
```

### 5. Provide Context in Error Messages

```javascript
// Good
ARGPortal.showError('Failed to save proposal. Please check your internet connection and try again.');

// Avoid
ARGPortal.showError('Error');
```

## Migration from Existing Systems

### From showtoastmessage Function

The ARGPortal system automatically detects and uses the existing `showtoastmessage` function if available:

```javascript
// Old way
showtoastmessage({
    message: 'Success message',
    type: 'success'
});

// New way (automatically uses showtoastmessage if available)
ARGPortal.showSuccess('Success message');
```

### From Alert Dialogs

```javascript
// Old way
alert('Operation completed successfully');

// New way
ARGPortal.showSuccess('Operation completed successfully');
```

## Troubleshooting

### Notifications Not Appearing

1. Check if the ARGPortal script is loaded:
   ```javascript
   console.log(typeof ARGPortal); // Should output 'object'
   ```

2. Check for JavaScript errors in the browser console

3. Ensure Bootstrap CSS is loaded for proper styling

### Notifications Appearing Twice

This might happen if both the old and new notification systems are active. Check for duplicate notification calls in your code.

### Custom Styling Not Applied

Make sure your custom CSS is loaded after the ARGPortal notification script and Bootstrap CSS.

## API Reference

### Core Methods

- `ARGPortal.showSuccess(message, duration?)`
- `ARGPortal.showError(message, duration?)`
- `ARGPortal.showWarning(message, duration?)`
- `ARGPortal.showInfo(message, duration?)`
- `ARGPortal.notify(message, type?, duration?)`

### Proposal Methods

- `ARGPortal.proposal.submitted(proposalTitle)`
- `ARGPortal.proposal.saved()`
- `ARGPortal.proposal.approved(proposalTitle)`
- `ARGPortal.proposal.rejected(proposalTitle, reason?)`
- `ARGPortal.proposal.changesRequested(proposalTitle)`

### User Methods

- `ARGPortal.user.loggedIn(username)`
- `ARGPortal.user.loggedOut()`
- `ARGPortal.user.profileUpdated()`
- `ARGPortal.user.passwordChanged()`

### Research Methods

- `ARGPortal.research.projectCreated(projectTitle)`
- `ARGPortal.research.progressUpdated()`
- `ARGPortal.research.fundingApproved(amount)`
- `ARGPortal.research.deadlineReminder(deadline, days)`

### System Methods

- `ARGPortal.system.maintenance(message)`
- `ARGPortal.system.update(message)`
- `ARGPortal.system.error(message)`

## Support

For issues or questions about the ARGPortal notification system, please contact the development team or refer to the project documentation.