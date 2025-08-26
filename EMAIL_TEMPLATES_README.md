# Email Templates - Kabianga ARG Portal

This document outlines the improved email template system for the University of Kabianga Annual Research Grants Portal.

## Template Structure

### Master Layout
- **File**: `resources/views/emails/layouts/master.blade.php`
- **Purpose**: Base template with University branding and responsive design
- **Features**: 
  - Modern, professional design
  - University of Kabianga branding
  - Responsive layout for mobile devices
  - Consistent header, footer, and styling

### Email Templates

#### 1. Proposal Submitted
- **File**: `resources/views/emails/proposal-submitted.blade.php`
- **Mail Class**: `App\Mail\ProposalSubmittedMail`
- **Purpose**: Confirmation email when a proposal is submitted
- **Variables**: `$user`, `$proposal`

#### 2. Proposal Approved
- **File**: `resources/views/emails/proposal-approved.blade.php`
- **Mail Class**: `App\Mail\ProposalApprovedMail`
- **Purpose**: Congratulatory email for approved proposals
- **Variables**: `$user`, `$proposal`
- **Features**: Success styling with green accents

#### 3. Proposal Rejected
- **File**: `resources/views/emails/proposal-rejected.blade.php`
- **Purpose**: Professional rejection email with constructive feedback
- **Variables**: `$user`, `$proposal`, `$feedback` (optional)
- **Features**: Constructive messaging and next steps

#### 4. Progress Reminder
- **File**: `resources/views/emails/progress-reminder.blade.php`
- **Mail Class**: `App\Mail\ProgressReminderMail`
- **Purpose**: Reminder for upcoming progress reports
- **Variables**: `$user`, `$project`, `$dueDate`, `$reportType`, `$daysRemaining`

#### 5. Welcome Email
- **File**: `resources/views/emails/welcome.blade.php`
- **Mail Class**: `App\Mail\WelcomeMail`
- **Purpose**: Welcome new users to the platform
- **Variables**: `$user`

#### 6. Password Reset
- **File**: `resources/views/emails/password-reset.blade.php`
- **Purpose**: Secure password reset instructions
- **Variables**: `$user`, `$resetUrl`

#### 7. Account Verification
- **File**: `resources/views/emails/verify-account.blade.php`
- **Mail Class**: `App\Mail\VerifyAccountMail`
- **Purpose**: Email verification for new accounts
- **Variables**: `$user`, `$verificationUrl`

#### 8. Proposal Changes Requested
- **File**: `resources/views/emails/proposal-changes-requested.blade.php`
- **Mail Class**: `App\Mail\ProposalChangesRequestedMail`
- **Purpose**: Request revisions to submitted proposals
- **Variables**: `$user`, `$proposal`, `$changes`, `$deadline`

#### 9. Project Started
- **File**: `resources/views/emails/project-started.blade.php`
- **Mail Class**: `App\Mail\ProjectStartedMail`
- **Purpose**: Notification when project officially begins
- **Variables**: `$user`, `$project`

#### 10. Project Completed
- **File**: `resources/views/emails/project-completed.blade.php`
- **Mail Class**: `App\Mail\ProjectCompletedMail`
- **Purpose**: Congratulations on project completion
- **Variables**: `$user`, `$project`

#### 11. Funding Opportunity
- **File**: `resources/views/emails/funding-opportunity.blade.php`
- **Mail Class**: `App\Mail\FundingOpportunityMail`
- **Purpose**: Notify about new funding opportunities
- **Variables**: `$user`, `$grant`

#### 12. Deadline Reminder
- **File**: `resources/views/emails/deadline-reminder.blade.php`
- **Mail Class**: `App\Mail\DeadlineReminderMail`
- **Purpose**: Urgent reminders for approaching deadlines
- **Variables**: `$user`, `$item_type`, `$title`, `$due_date`, `$days_remaining`, `$description`, `$action_url`

#### 13. Report Submitted
- **File**: `resources/views/emails/report-submitted.blade.php`
- **Mail Class**: `App\Mail\ReportSubmittedMail`
- **Purpose**: Confirmation of report submissions
- **Variables**: `$user`, `$project`, `$report_type`

#### 14. Account Suspended
- **File**: `resources/views/emails/account-suspended.blade.php`
- **Purpose**: Notification of account suspension
- **Variables**: `$user`, `$reason`, `$duration`, `$details`

#### 15. General Notification
- **File**: `resources/views/emails/general-notification.blade.php`
- **Purpose**: Flexible template for various notifications
- **Variables**: `$subject`, `$greeting`, `$content`, `$actionUrl`, `$actionText`, `$additionalInfo`, `$footer`

## Usage Examples

### Sending a Proposal Submitted Email
```php
use App\Mail\ProposalSubmittedMail;
use Illuminate\Support\Facades\Mail;

Mail::to($user->email)->send(new ProposalSubmittedMail($user, $proposal));
```

### Sending a Welcome Email
```php
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

Mail::to($user->email)->send(new WelcomeMail($user));
```

### Using the General Notification Template
```php
use App\Mail\ArgMail;
use Illuminate\Support\Facades\Mail;

$mail = new ArgMail(
    'Your Subject Here',
    'Your message content here...',
    'https://portal.kabianga.ac.ke/action',
    'Take Action'
);

Mail::to($user->email)->send($mail);
```

## Design Features

### Visual Elements
- **Colors**: University blue (#1e40af) as primary color
- **Typography**: Modern sans-serif fonts (Segoe UI, Tahoma, Geneva, Verdana)
- **Layout**: Clean, professional design with proper spacing
- **Branding**: University of Kabianga header and contact information

### Responsive Design
- Mobile-friendly layout
- Optimized for various email clients
- Proper viewport settings
- Scalable buttons and text

### Accessibility
- High contrast colors
- Readable font sizes
- Clear call-to-action buttons
- Semantic HTML structure

## Customization

### Adding New Templates
1. Create a new Blade template in `resources/views/emails/`
2. Extend the master layout: `@extends('emails.layouts.master')`
3. Define content in the `@section('content')` block
4. Create corresponding Mail class in `app/Mail/`

### Styling Guidelines
- Use the existing CSS classes from the master layout
- Maintain consistent spacing and colors
- Follow the University branding guidelines
- Test across different email clients

## Email Client Compatibility

The templates are designed to work across major email clients:
- Gmail
- Outlook (desktop and web)
- Apple Mail
- Yahoo Mail
- Mobile email clients

## Security Considerations

- All URLs are properly escaped
- User input is sanitized
- No external resources loaded
- Inline CSS for better compatibility

## Maintenance

### Regular Updates
- Review templates quarterly for design consistency
- Update contact information as needed
- Test templates with new email clients
- Monitor delivery rates and user feedback

### Performance
- Optimized image sizes (if any)
- Minimal CSS for faster loading
- Clean HTML structure
- Compressed inline styles