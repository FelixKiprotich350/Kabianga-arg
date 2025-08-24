# Kabianga ARG Portal - Notifications System

Comprehensive notification system supporting both in-app and email notifications.

## Overview

The notification system provides dual-channel communication (in-app + email) for all major system events including proposal submissions, approvals, project updates, and system announcements.

## Architecture

### Core Components

1. **DualNotificationService** - Main service for sending notifications
2. **NotificationService** - Legacy service (being phased out)
3. **NotifiesUsers Trait** - Provides notification methods to models
4. **Notification Model** - Stores in-app notifications
5. **Email Notifications** - Laravel notification classes for emails

### Notification Flow

```
Event Triggered → NotifiesUsers Trait → DualNotificationService → In-app + Email
```

## Configuration

### User Preferences

Users can control notification preferences via their profile:

```php
// User model fields
$user->email_notifications = true;  // Enable/disable email notifications
$user->inapp_notifications = true;  // Enable/disable in-app notifications
```

### Email Configuration

Configure email settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kabianga.ac.ke
MAIL_FROM_NAME="ARG Portal"
```

## Usage

### Using the NotifiesUsers Trait

```php
use App\Traits\NotifiesUsers;

class ProposalController extends Controller
{
    use NotifiesUsers;
    
    public function submitProposal($proposal)
    {
        // Submit proposal logic...
        
        // Send notification
        $this->notifyProposalSubmitted($proposal);
    }
}
```

### Direct Service Usage

```php
use App\Services\DualNotificationService;

// Single user notification
DualNotificationService::notify(
    $user,
    'proposal_approved',
    'Proposal Approved',
    'Your research proposal has been approved.',
    route('proposals.view', $proposal->id)
);

// Multiple users notification
DualNotificationService::notifyMultiple(
    $users,
    'system_maintenance',
    'System Maintenance',
    'System will be down for maintenance on Sunday.',
    null
);
```

## Available Notification Types

### Proposal Notifications

| Method | Trigger | Recipients |
|--------|---------|------------|
| `notifyProposalSubmitted()` | Proposal submitted | Admins, Reviewers |
| `notifyProposalReceived()` | Proposal received | Applicant |
| `notifyProposalApproved()` | Proposal approved | Applicant |
| `notifyProposalRejected()` | Proposal rejected | Applicant |
| `notifyChangesRequested()` | Changes requested | Applicant |

### Project Notifications

| Method | Trigger | Recipients |
|--------|---------|------------|
| `notifyProjectStatusChanged()` | Status change | Project owner |
| `notifyProjectAssigned()` | Supervisor assigned | Owner, Supervisor |
| `notifyProgressSubmitted()` | Progress submitted | Supervisors, Admins |
| `notifyFundingAdded()` | Funding added | Project owner |

### User Management Notifications

| Method | Trigger | Recipients |
|--------|---------|------------|
| `notifyUserCreated()` | Account created | New user |
| `notifyUserRoleChanged()` | Role updated | User |
| `notifyUserPermissionsChanged()` | Permissions updated | User |
| `notifyUserDisabled()` | Account disabled | User |
| `notifyUserEnabled()` | Account reactivated | User |

### System Notifications

| Method | Trigger | Recipients |
|--------|---------|------------|
| `notifySystemMaintenance()` | Maintenance scheduled | All users |
| `notifyDeadlineReminder()` | Deadline approaching | Relevant users |
| `notifyNewGrantAvailable()` | New grant added | Researchers |
| `notifyThemeUpdated()` | Theme modified | Related users |

## Notification Data Structure

### In-App Notification

```php
// Database structure
[
    'id' => 1,
    'user_id' => 123,
    'type' => 'proposal_approved',
    'title' => 'Proposal Approved',
    'message' => 'Your research proposal has been approved.',
    'data' => [
        'actionUrl' => '/proposals/view/456',
        'actionText' => 'View Proposal',
        'level' => 'success'
    ],
    'read_at' => null,
    'created_at' => '2024-01-15 10:30:00'
]
```

### Email Notification

```php
// Email notification structure
[
    'subject' => 'Proposal Approved',
    'greeting' => 'Hello John Doe,',
    'level' => 'success',
    'introLines' => ['Your research proposal has been approved.'],
    'actionUrl' => '/proposals/view/456',
    'actionText' => 'View Proposal',
    'outroLines' => ['Thank you for using the ARG Portal.'],
    'salutation' => 'Best regards, University of Kabianga'
]
```

## Frontend Integration

### JavaScript API

```javascript
// Fetch unread notifications
async function fetchNotifications() {
    const response = await fetch('/api/v1/notifications');
    const data = await response.json();
    return data.notifications;
}

// Mark notification as read
async function markAsRead(notificationId) {
    await fetch(`/api/v1/notifications/${notificationId}/read`, {
        method: 'PATCH'
    });
}

// Real-time notifications (if using WebSockets)
window.Echo.private(`user.${userId}`)
    .notification((notification) => {
        showNotificationToast(notification);
        updateNotificationBadge();
    });
```

### Notification Display

```html
<!-- Notification dropdown -->
<div class="notification-dropdown">
    <div class="notification-item unread" data-id="123">
        <div class="notification-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content">
            <h6>Proposal Approved</h6>
            <p>Your research proposal has been approved.</p>
            <small>2 hours ago</small>
        </div>
    </div>
</div>
```

## Queue Configuration

For better performance, notifications are processed in background queues:

```php
// config/queue.php
'default' => env('QUEUE_CONNECTION', 'database'),

'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
    ],
],
```

Start queue worker:
```bash
php artisan queue:work
```

## Customization

### Custom Notification Types

1. Create notification class:
```php
php artisan make:notification CustomNotification
```

2. Implement notification:
```php
class CustomNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Custom Notification')
            ->line('Your custom message here.');
    }
    
    public function toArray($notifiable)
    {
        return [
            'title' => 'Custom Notification',
            'message' => 'Your custom message here.'
        ];
    }
}
```

### Email Templates

Customize email templates in `resources/views/emails/`:

```blade
{{-- resources/views/emails/custom-notification.blade.php --}}
@component('mail::message')
# {{ $title }}

{{ $message }}

@if($actionUrl)
@component('mail::button', ['url' => $actionUrl])
{{ $actionText ?? 'View Details' }}
@endcomponent
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
```

## Testing

### Unit Tests

```php
// Test notification sending
public function test_proposal_approval_sends_notification()
{
    Notification::fake();
    
    $user = User::factory()->create();
    $proposal = Proposal::factory()->create(['applicant_id' => $user->id]);
    
    $this->notifyProposalApproved($proposal);
    
    Notification::assertSentTo($user, ProposalApprovedNotification::class);
}
```

### Manual Testing

```php
// Test notification in tinker
php artisan tinker

$user = User::find(1);
DualNotificationService::notify(
    $user,
    'test',
    'Test Notification',
    'This is a test notification.',
    '/dashboard'
);
```

## Troubleshooting

### Common Issues

1. **Emails not sending**
   - Check MAIL configuration in `.env`
   - Verify SMTP credentials
   - Check queue worker is running

2. **In-app notifications not appearing**
   - Verify user preferences
   - Check database connection
   - Ensure notifications table exists

3. **Queue jobs failing**
   - Check `failed_jobs` table
   - Restart queue worker
   - Check log files in `storage/logs/`

### Debug Commands

```bash
# Check queue status
php artisan queue:work --verbose

# Process failed jobs
php artisan queue:retry all

# Clear notification cache
php artisan cache:clear
```

## Performance Optimization

1. **Use queues** for email notifications
2. **Batch notifications** for multiple users
3. **Clean up old notifications** regularly
4. **Index database** notification tables
5. **Cache notification counts** for better performance