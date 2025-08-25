# Screenshot Guide for Kabianga ARG Portal Presentation

## Overview
This guide provides instructions for capturing screenshots of the Kabianga ARG Portal to create a comprehensive presentation with visual elements.

## Prerequisites
1. Ensure the application is running (`php artisan serve`)
2. Have sample data in the database (run seeders if needed)
3. Create test user accounts for different roles
4. Use a clean browser window for consistent screenshots

## Screenshot Checklist

### 1. Authentication Screenshots

#### Login Page
- **URL**: `http://localhost:8000/login`
- **Elements to capture**:
  - Login form with email/password fields
  - "Remember Me" checkbox
  - "Forgot Password?" link
  - University logo and branding
- **Filename**: `01_login_page.png`

#### Registration Page
- **URL**: `http://localhost:8000/register`
- **Elements to capture**:
  - Registration form (Name, Email, PF Number, Password)
  - Form validation messages
  - Submit button
- **Filename**: `02_registration_page.png`

#### Password Reset Page
- **URL**: `http://localhost:8000/password/reset`
- **Elements to capture**:
  - Email input field
  - "Send Reset Link" button
  - Instructions text
- **Filename**: `03_password_reset.png`

### 2. Dashboard Screenshots

#### Administrator Dashboard
- **URL**: `http://localhost:8000/dashboard` (login as admin)
- **Elements to capture**:
  - Statistics cards (Total, Approved, Pending, Rejected)
  - Charts showing proposals by theme
  - Recent activity panel
  - Quick action buttons
- **Filename**: `04_admin_dashboard.png`

#### Researcher Dashboard
- **URL**: `http://localhost:8000/dashboard` (login as researcher)
- **Elements to capture**:
  - Personal statistics
  - Quick actions (New Application, My Applications, My Projects)
  - Notification area
- **Filename**: `05_researcher_dashboard.png`

### 3. Proposal Management Screenshots

#### Proposal List
- **URL**: `http://localhost:8000/proposals`
- **Elements to capture**:
  - Table with proposals
  - Status indicators (color-coded)
  - Action buttons (View, Edit)
  - Search and filter options
- **Filename**: `06_proposal_list.png`

#### New Proposal Form - Basic Information
- **URL**: `http://localhost:8000/proposals/newproposal`
- **Elements to capture**:
  - Grant selection dropdown
  - Research theme selection
  - Project title field
  - Description textarea
  - Navigation steps indicator
- **Filename**: `07_new_proposal_basic.png`

#### New Proposal Form - Collaborators
- **Elements to capture**:
  - Collaborator addition form
  - List of added collaborators
  - Internal/External collaborator options
- **Filename**: `08_new_proposal_collaborators.png`

#### New Proposal Form - Budget
- **Elements to capture**:
  - Budget categories (Personnel, Equipment, Travel, Other)
  - Amount input fields
  - Total budget calculation
  - Add/Remove budget items
- **Filename**: `09_new_proposal_budget.png`

#### Proposal View/Details
- **URL**: `http://localhost:8000/proposals/view/{id}`
- **Elements to capture**:
  - Complete proposal information
  - Status indicators
  - Action buttons (Edit, Submit, etc.)
  - Comments section
- **Filename**: `10_proposal_details.png`

#### Proposal Review Interface
- **Elements to capture**:
  - Proposal details for review
  - Review action buttons (Approve, Reject, Request Changes)
  - Comment/feedback form
  - Review history
- **Filename**: `11_proposal_review.png`

### 4. Project Management Screenshots

#### Project List
- **URL**: `http://localhost:8000/projects`
- **Elements to capture**:
  - Project table with status
  - Progress indicators
  - Project details
  - Filter options
- **Filename**: `12_project_list.png`

#### Project Details
- **URL**: `http://localhost:8000/projects/myprojects/{id}`
- **Elements to capture**:
  - Project overview information
  - Progress tracking
  - Team members
  - Milestone status
- **Filename**: `13_project_details.png`

#### Project Progress Tracking
- **Elements to capture**:
  - Progress percentage
  - Milestone timeline
  - Completion status
  - Update forms
- **Filename**: `14_project_progress.png`

### 5. User Management Screenshots

#### User List
- **URL**: `http://localhost:8000/users/manage`
- **Elements to capture**:
  - User table with roles
  - Status indicators (Active/Inactive)
  - Action buttons
  - Search functionality
- **Filename**: `15_user_management.png`

#### User Profile
- **URL**: `http://localhost:8000/myprofile`
- **Elements to capture**:
  - Profile information form
  - Contact details
  - Account settings
  - Notification preferences
- **Filename**: `16_user_profile.png`

#### User Permissions
- **URL**: `http://localhost:8000/users/{id}/permissions`
- **Elements to capture**:
  - Permission list with checkboxes
  - Role assignment
  - Permission categories
- **Filename**: `17_user_permissions.png`

### 6. Grant Management Screenshots

#### Grant List
- **URL**: `http://localhost:8000/grants/home`
- **Elements to capture**:
  - Available grants table
  - Grant details (Amount, Deadline)
  - Application status
- **Filename**: `18_grant_list.png`

#### Grant Details
- **URL**: `http://localhost:8000/grants/view/{id}`
- **Elements to capture**:
  - Grant information
  - Eligibility criteria
  - Application requirements
  - Deadline information
- **Filename**: `19_grant_details.png`

### 7. Reports & Analytics Screenshots

#### Financial Reports Dashboard
- **URL**: `http://localhost:8000/reports/financial`
- **Elements to capture**:
  - Financial charts and graphs
  - Budget allocation tables
  - Expenditure analysis
  - Filter options
- **Filename**: `20_financial_reports.png`

#### Advanced Reports
- **URL**: `http://localhost:8000/reports/advanced`
- **Elements to capture**:
  - Report generation interface
  - Various report types
  - Export options
  - Date range selectors
- **Filename**: `21_advanced_reports.png`

### 8. Notification Screenshots

#### Notification Center
- **URL**: `http://localhost:8000/notifications`
- **Elements to capture**:
  - Notification list
  - Read/Unread indicators
  - Notification types
  - Mark as read options
- **Filename**: `22_notifications.png`

#### In-App Notifications
- **Elements to capture**:
  - Notification dropdown
  - Recent notifications
  - Notification bell icon with count
- **Filename**: `23_notification_dropdown.png`

### 9. System Administration Screenshots

#### Schools Management
- **URL**: `http://localhost:8000/schools`
- **Elements to capture**:
  - Schools list
  - Department hierarchy
  - Add/Edit options
- **Filename**: `24_schools_management.png`

#### Research Themes
- **URL**: `http://localhost:8000/themes`
- **Elements to capture**:
  - Theme categories
  - Theme descriptions
  - Management options
- **Filename**: `25_research_themes.png`

#### Financial Years
- **URL**: `http://localhost:8000/financial-years`
- **Elements to capture**:
  - Financial year list
  - Budget allocations
  - Year status
- **Filename**: `26_financial_years.png`

## Screenshot Best Practices

### Technical Settings
- **Browser**: Use Chrome or Firefox for consistency
- **Resolution**: 1920x1080 or higher
- **Zoom Level**: 100% (default)
- **Window Size**: Maximized for full-screen captures

### Visual Guidelines
- **Clean Interface**: Remove browser bookmarks bar
- **Consistent Data**: Use realistic sample data
- **Highlight Important Elements**: Use browser dev tools to highlight key areas
- **Multiple States**: Capture different states (empty, populated, error states)

### Capture Tools
- **Built-in Tools**: Browser screenshot tools
- **Third-party Tools**: Lightshot, Snagit, or similar
- **Full Page**: Use tools that can capture full page scrolls
- **Annotations**: Add callouts and highlights as needed

## Sample Data Requirements

### Users
Create sample users for different roles:
```
Admin: admin@kabianga.ac.ke
Researcher: researcher@kabianga.ac.ke
Committee: committee@kabianga.ac.ke
Supervisor: supervisor@kabianga.ac.ke
```

### Proposals
Create sample proposals in different states:
- Draft proposals
- Submitted proposals
- Under review proposals
- Approved proposals
- Rejected proposals

### Projects
Create sample projects with:
- Different progress levels
- Various team sizes
- Different funding amounts
- Multiple milestones

## Post-Processing

### Image Editing
- **Resize**: Standardize image dimensions
- **Crop**: Remove unnecessary browser chrome
- **Annotate**: Add callouts and explanations
- **Blur**: Hide sensitive information if needed

### Organization
- **Naming Convention**: Use descriptive filenames
- **Folder Structure**: Organize by module/section
- **Version Control**: Keep original and edited versions
- **Documentation**: Note what each screenshot shows

## Integration with Presentation

### PowerPoint/Google Slides
- **Import**: Add screenshots to appropriate slides
- **Layout**: Use consistent positioning
- **Captions**: Add descriptive captions
- **Animations**: Consider slide transitions

### Documentation
- **Reference**: Link screenshots to documentation sections
- **Context**: Provide step-by-step explanations
- **Updates**: Keep screenshots current with system changes

## Quality Checklist

Before finalizing screenshots:
- [ ] All text is readable
- [ ] No sensitive data is visible
- [ ] Images are high resolution
- [ ] Consistent browser/interface appearance
- [ ] All key features are highlighted
- [ ] Screenshots match current system version
- [ ] File names are descriptive and organized
- [ ] Images are optimized for presentation use

## Maintenance

### Regular Updates
- **System Changes**: Update screenshots when features change
- **New Features**: Add screenshots for new functionality
- **UI Updates**: Refresh screenshots after design changes
- **Data Updates**: Use current, relevant sample data

### Version Control
- **Backup**: Keep copies of all screenshots
- **Dating**: Include capture dates in metadata
- **Changelog**: Document what changed in each update
- **Archive**: Keep historical versions for reference

This guide ensures comprehensive visual documentation of the Kabianga ARG Portal for effective presentations and training materials.