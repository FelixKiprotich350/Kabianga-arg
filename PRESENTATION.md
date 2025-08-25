# Kabianga Annual Research Grants Portal
## Comprehensive System Presentation

---

## Table of Contents
1. [System Overview](#system-overview)
2. [System Architecture](#system-architecture)
3. [User Authentication & Authorization](#user-authentication--authorization)
4. [Dashboard Module](#dashboard-module)
5. [Proposal Management](#proposal-management)
6. [Project Management](#project-management)
7. [User Management](#user-management)
8. [Grant Management](#grant-management)
9. [Reports & Analytics](#reports--analytics)
10. [Notification System](#notification-system)
11. [System Administration](#system-administration)
12. [API Documentation](#api-documentation)
13. [Installation Guide](#installation-guide)
14. [User Guide](#user-guide)

---

## System Overview

### What is Kabianga ARG Portal?
The Kabianga Annual Research Grants Portal is a comprehensive web-based system designed to streamline the entire research lifecycle at the University of Kabianga, from proposal submission to project completion and reporting.

### Key Benefits
- **Streamlined Process**: Automated workflow from proposal to completion
- **Role-Based Access**: Secure access control for different user types
- **Real-time Tracking**: Monitor proposals and projects in real-time
- **Comprehensive Reporting**: Generate detailed analytics and reports
- **Notification System**: Automated email and in-app notifications
- **PDF Generation**: Automated document generation

### Target Users
- **Researchers**: Submit and manage research proposals
- **Administrators**: Oversee the entire research process
- **Committee Members**: Review and approve proposals
- **Supervisors**: Monitor project progress
- **Finance Officers**: Manage funding and expenditures

---

## System Architecture

### Technology Stack
```
Frontend: Blade Templates + Bootstrap + JavaScript
Backend: Laravel 10.x (PHP 8.1+)
Database: MySQL 8.0+
PDF Generation: Snappy/wkhtmltopdf
Queue System: Laravel Queues
Notifications: Laravel Notifications (Email + In-app)
```

### Core Components
1. **Authentication Layer**: Secure login with role-based permissions
2. **Business Logic Layer**: Controllers and Services
3. **Data Layer**: Eloquent Models and Database
4. **Presentation Layer**: Blade Templates and APIs
5. **Notification Layer**: Email and In-app notifications
6. **PDF Generation**: Document generation service

### Database Schema Overview
- **Users**: System users with roles and permissions
- **Proposals**: Research proposal submissions
- **Projects**: Active research projects
- **Grants**: Available funding opportunities
- **Notifications**: System notifications
- **Permissions**: Role-based access control

---

## User Authentication & Authorization

### Login Process
**Screenshot Placeholder: Login Page**
*[Login form with email/password fields, remember me option, and forgot password link]*

#### Step-by-Step Login Guide:
1. Navigate to the portal URL
2. Enter your registered email address
3. Enter your password
4. Click "Login" button
5. System redirects to appropriate dashboard based on role

### Registration Process
**Screenshot Placeholder: Registration Page**
*[Registration form with name, email, PF number, password fields]*

#### Step-by-Step Registration Guide:
1. Click "Register" on login page
2. Fill in required information:
   - Full Name
   - Email Address
   - PF Number
   - Password (minimum 8 characters)
   - Confirm Password
3. Click "Register" button
4. Check email for verification link
5. Click verification link to activate account

### Password Reset
**Screenshot Placeholder: Password Reset Page**
*[Password reset form with email field]*

#### Step-by-Step Password Reset:
1. Click "Forgot Password?" on login page
2. Enter your registered email address
3. Click "Send Reset Link"
4. Check email for reset instructions
5. Click reset link and enter new password
6. Confirm new password and save

### Role-Based Access Control
The system implements comprehensive role-based permissions:

#### User Roles:
- **Super Administrator**: Full system access
- **Administrator**: Manage users, grants, and system settings
- **Committee Member**: Review and approve proposals
- **Researcher**: Submit and manage proposals
- **Finance Officer**: Manage funding and expenditures
- **Supervisor**: Monitor project progress

#### Permission System:
```php
// Example permissions
- cansubmitproposal
- canapproveproposal
- canrejectproposal
- canviewallproposals
- canmanageusers
- canviewreports
- canmanagegrants
```

---

## Dashboard Module

### Administrator Dashboard
**Screenshot Placeholder: Admin Dashboard**
*[Dashboard with statistics cards, charts, and recent activity]*

#### Features:
- **Statistics Overview**: Total proposals, approved, pending, rejected
- **Visual Charts**: Proposals by research theme, monthly trends
- **Recent Activity**: Latest system activities
- **Quick Actions**: Access to key functions

#### Step-by-Step Dashboard Navigation:
1. Login to the system
2. Dashboard loads automatically after login
3. View key statistics in the top cards
4. Analyze trends using the interactive charts
5. Monitor recent activities in the activity panel
6. Use quick action buttons for common tasks

### Researcher Dashboard
**Screenshot Placeholder: Researcher Dashboard**
*[User dashboard with personal statistics and quick actions]*

#### Features:
- **My Applications Status**: Personal proposal statistics
- **Quick Actions**: Submit new proposal, view applications
- **Project Overview**: Active projects summary
- **Notifications**: Recent system notifications

#### Dashboard Components:
1. **Statistics Cards**:
   - Total Applications
   - Approved Applications
   - Pending Applications
   - Active Projects

2. **Quick Actions**:
   - New Application Button
   - My Applications Link
   - My Projects Link

---

## Proposal Management

### Proposal Submission Process
**Screenshot Placeholder: New Proposal Form**
*[Multi-step proposal form with sections for basic info, collaborators, budget]*

#### Step-by-Step Proposal Submission:
1. **Access Proposal Form**:
   - Click "New Application" from dashboard
   - Or navigate to Proposals > New Proposal

2. **Basic Information**:
   - Select Grant/Funding Opportunity
   - Choose Research Theme
   - Enter Project Title
   - Provide Project Description
   - Set Project Duration

3. **Applicant Details**:
   - Personal Information (auto-filled)
   - Contact Information
   - Highest Qualification
   - Department/School

4. **Collaborators Section**:
   - Add internal collaborators
   - Add external collaborators
   - Specify roles and contributions

5. **Publications Section**:
   - List relevant publications
   - Add publication details
   - Upload supporting documents

6. **Budget/Expenditure**:
   - Personnel costs
   - Equipment costs
   - Travel expenses
   - Other expenses
   - Total budget calculation

7. **Research Design**:
   - Methodology description
   - Research approach
   - Expected outcomes

8. **Work Plan**:
   - Project timeline
   - Milestones
   - Deliverables

9. **Submit Proposal**:
   - Review all sections
   - Click "Submit for Review"
   - Confirmation message displayed

### Proposal Status Tracking
**Screenshot Placeholder: Proposal List**
*[Table showing proposals with status indicators]*

#### Proposal Statuses:
- **Draft**: Proposal being prepared
- **Submitted**: Submitted for review
- **Received**: Acknowledged by administration
- **Under Review**: Being evaluated
- **Approved**: Accepted for funding
- **Rejected**: Not approved
- **Changes Requested**: Requires modifications

#### Tracking Features:
- Status indicators with color coding
- Timeline view of proposal progress
- Email notifications for status changes
- Comments and feedback system

### Proposal Review Process
**Screenshot Placeholder: Proposal Review Interface**
*[Proposal details with review options and comment section]*

#### Step-by-Step Review Process:
1. **Access Proposals**:
   - Navigate to Proposals section
   - View list of submitted proposals

2. **Review Proposal**:
   - Click on proposal to view details
   - Review all sections thoroughly
   - Check budget calculations
   - Verify research methodology

3. **Committee Actions**:
   - **Receive Proposal**: Acknowledge receipt
   - **Request Changes**: Ask for modifications
   - **Approve**: Accept the proposal
   - **Reject**: Decline the proposal

4. **Add Comments**:
   - Provide feedback to applicant
   - Suggest improvements
   - Ask clarifying questions

---

## Project Management

### Project Creation
**Screenshot Placeholder: Project Creation Form**
*[Form to convert approved proposal to active project]*

#### Automatic Project Creation:
- Approved proposals automatically become projects
- Project inherits proposal details
- Initial funding allocation set
- Project timeline established

### Project Monitoring
**Screenshot Placeholder: Project Dashboard**
*[Project overview with progress indicators and funding status]*

#### Project Features:
1. **Project Overview**:
   - Basic project information
   - Current status
   - Progress percentage
   - Key milestones

2. **Funding Management**:
   - Total allocated budget
   - Funds disbursed
   - Remaining balance
   - Expenditure tracking

3. **Progress Tracking**:
   - Milestone completion
   - Timeline adherence
   - Deliverable status
   - Performance metrics

4. **Team Management**:
   - Project team members
   - Role assignments
   - Contact information
   - Collaboration tools

#### Step-by-Step Project Monitoring:
1. **Access Projects**:
   - Navigate to Projects section
   - Select specific project

2. **Update Progress**:
   - Mark milestones as complete
   - Update progress percentage
   - Add progress notes

3. **Manage Funding**:
   - Request fund disbursement
   - Record expenditures
   - Upload receipts/invoices

4. **Generate Reports**:
   - Progress reports
   - Financial reports
   - Milestone reports

### Project Supervision
**Screenshot Placeholder: Supervision Interface**
*[Supervisor view with multiple projects and oversight tools]*

#### Supervision Features:
- **Multi-project Overview**: Monitor multiple projects
- **Progress Alerts**: Notifications for delays
- **Budget Monitoring**: Track spending patterns
- **Performance Analytics**: Project success metrics

---

## User Management

### User Registration & Verification
**Screenshot Placeholder: User Management Interface**
*[Admin interface showing user list with status and actions]*

#### User Management Features:
1. **User Registration**:
   - Self-registration with email verification
   - Admin approval process
   - Role assignment

2. **User Verification**:
   - Email verification required
   - Account activation by admin
   - Profile completion check

3. **Role Management**:
   - Assign user roles
   - Modify permissions
   - Role-based access control

#### Step-by-Step User Management:
1. **View All Users**:
   - Navigate to Users > Manage Users
   - View user list with status

2. **User Actions**:
   - **Activate/Deactivate**: Control user access
   - **Assign Roles**: Set user permissions
   - **View Profile**: Check user details
   - **Reset Password**: Help with access issues

3. **Permission Management**:
   - View user permissions
   - Modify access rights
   - Assign special permissions

### Profile Management
**Screenshot Placeholder: User Profile Page**
*[User profile form with personal and professional information]*

#### Profile Features:
- **Personal Information**: Name, contact details
- **Professional Details**: Department, qualifications
- **Account Settings**: Password change, preferences
- **Notification Settings**: Email preferences

---

## Grant Management

### Grant Creation
**Screenshot Placeholder: Grant Management Interface**
*[Form to create new grant opportunities]*

#### Grant Features:
1. **Grant Information**:
   - Grant title and description
   - Funding amount
   - Application deadline
   - Eligibility criteria

2. **Application Settings**:
   - Required documents
   - Review process
   - Approval workflow

#### Step-by-Step Grant Management:
1. **Create New Grant**:
   - Navigate to Grants section
   - Click "Add New Grant"
   - Fill grant details
   - Set application parameters

2. **Manage Applications**:
   - View grant applications
   - Track application status
   - Generate grant reports

### Financial Year Management
**Screenshot Placeholder: Financial Year Interface**
*[Interface showing financial years and associated grants]*

#### Features:
- **Financial Year Setup**: Define budget periods
- **Grant Allocation**: Assign funds to grants
- **Budget Tracking**: Monitor spending
- **Year-end Reports**: Financial summaries

---

## Reports & Analytics

### Financial Reports
**Screenshot Placeholder: Financial Dashboard**
*[Charts and tables showing financial data and trends]*

#### Report Types:
1. **Budget Allocation Reports**:
   - Total budget by grant
   - Allocation by research theme
   - Department-wise distribution

2. **Expenditure Reports**:
   - Actual vs. budgeted spending
   - Expense categories
   - Project-wise expenditure

3. **Financial Performance**:
   - Fund utilization rates
   - Budget variance analysis
   - Cost per project metrics

#### Step-by-Step Report Generation:
1. **Access Reports**:
   - Navigate to Reports section
   - Select report type

2. **Set Parameters**:
   - Choose date range
   - Select filters (grant, department, etc.)
   - Set report format

3. **Generate Report**:
   - Click "Generate Report"
   - View results on screen
   - Export to PDF/Excel

### Advanced Analytics
**Screenshot Placeholder: Analytics Dashboard**
*[Advanced charts showing trends and patterns]*

#### Analytics Features:
- **Proposal Success Rates**: Approval percentages
- **Research Theme Analysis**: Popular research areas
- **Timeline Analysis**: Project completion rates
- **Performance Metrics**: Key performance indicators

---

## Notification System

### Notification Types
**Screenshot Placeholder: Notifications Panel**
*[Notification center showing different types of notifications]*

#### System Notifications:
1. **Proposal Notifications**:
   - Proposal submitted
   - Proposal received
   - Status changes
   - Approval/rejection

2. **Project Notifications**:
   - Milestone deadlines
   - Budget alerts
   - Progress updates
   - Completion reminders

3. **System Notifications**:
   - Account verification
   - Password reset
   - System maintenance
   - Policy updates

#### Notification Delivery:
- **In-app Notifications**: Real-time alerts
- **Email Notifications**: Detailed messages
- **Notification Preferences**: User-controlled settings

### Step-by-Step Notification Management:
1. **View Notifications**:
   - Click notification bell icon
   - View recent notifications
   - Mark as read/unread

2. **Notification Settings**:
   - Access profile settings
   - Configure email preferences
   - Set notification types

---

## System Administration

### System Settings
**Screenshot Placeholder: Admin Settings Panel**
*[Administrative interface with system configuration options]*

#### Administrative Features:
1. **Global Settings**:
   - System configuration
   - Email settings
   - PDF generation settings
   - Notification preferences

2. **Permission Management**:
   - Define user roles
   - Set permission levels
   - Manage access control

3. **System Monitoring**:
   - User activity logs
   - System performance
   - Error monitoring
   - Backup management

### Department & School Management
**Screenshot Placeholder: Department Management**
*[Interface for managing academic departments and schools]*

#### Organizational Structure:
- **Schools**: Top-level academic divisions
- **Departments**: Sub-divisions within schools
- **User Assignment**: Link users to departments

### Research Theme Management
**Screenshot Placeholder: Theme Management**
*[Interface for managing research themes and categories]*

#### Theme Features:
- **Theme Categories**: Organize research areas
- **Theme Descriptions**: Detailed information
- **Proposal Mapping**: Link proposals to themes

---

## API Documentation

### API Overview
The system provides a comprehensive REST API for integration and mobile applications.

#### API Endpoints:
```
Authentication:
POST /api/v1/auth/login
POST /api/v1/auth/register
POST /api/v1/auth/logout

Proposals:
GET /api/v1/proposals
POST /api/v1/proposals
GET /api/v1/proposals/{id}
PUT /api/v1/proposals/{id}

Projects:
GET /api/v1/projects
GET /api/v1/projects/{id}
PUT /api/v1/projects/{id}/progress

Dashboard:
GET /api/v1/dashboard/stats
GET /api/v1/dashboard/charts
GET /api/v1/dashboard/activity

Reports:
GET /api/v1/reports/financial
GET /api/v1/reports/proposals
GET /api/v1/reports/projects
```

#### API Authentication:
- **Token-based Authentication**: Secure API access
- **Rate Limiting**: Prevent abuse
- **CORS Support**: Cross-origin requests

---

## Installation Guide

### System Requirements
- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **MySQL**: 8.0 or higher
- **Node.js**: 16.x or higher
- **wkhtmltopdf**: For PDF generation

### Step-by-Step Installation:

#### 1. Clone Repository
```bash
git clone <repository-url>
cd Kabianga-arg
```

#### 2. Install Dependencies
```bash
composer install
npm install
```

#### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Database Setup
```bash
# Configure database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kabianga_arg
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate
php artisan db:seed
```

#### 5. Build Assets
```bash
npm run build
```

#### 6. Start Application
```bash
php artisan serve
```

#### 7. Initial Setup
- Navigate to `/setupadmin`
- Create initial administrator account
- Configure system settings

---

## User Guide

### Getting Started

#### First-Time Users:
1. **Registration**:
   - Visit the portal URL
   - Click "Register"
   - Fill registration form
   - Verify email address

2. **Profile Setup**:
   - Complete profile information
   - Set notification preferences
   - Familiarize with dashboard

3. **First Proposal**:
   - Review available grants
   - Prepare proposal documents
   - Submit first application

#### Regular Users:
1. **Dashboard Navigation**:
   - Monitor proposal status
   - Check notifications
   - Access quick actions

2. **Proposal Management**:
   - Track application progress
   - Respond to feedback
   - Submit required documents

3. **Project Management**:
   - Update project progress
   - Manage team members
   - Submit reports

### Best Practices

#### For Researchers:
- **Proposal Preparation**:
  - Review grant requirements carefully
  - Prepare all documents in advance
  - Follow proposal guidelines
  - Submit before deadline

- **Project Management**:
  - Update progress regularly
  - Maintain accurate records
  - Communicate with supervisors
  - Submit reports on time

#### For Administrators:
- **User Management**:
  - Verify user credentials
  - Assign appropriate roles
  - Monitor user activity
  - Provide support when needed

- **System Maintenance**:
  - Regular backups
  - Monitor system performance
  - Update system settings
  - Generate regular reports

### Troubleshooting

#### Common Issues:
1. **Login Problems**:
   - Check email/password
   - Verify account activation
   - Clear browser cache
   - Contact administrator

2. **Proposal Submission**:
   - Check required fields
   - Verify file formats
   - Ensure stable internet
   - Save progress frequently

3. **Notification Issues**:
   - Check email settings
   - Verify notification preferences
   - Check spam folder
   - Update contact information

---

## Support & Maintenance

### Technical Support
- **Email**: support@kabianga.ac.ke
- **Phone**: +254-XXX-XXXX
- **Office Hours**: Monday-Friday, 8:00 AM - 5:00 PM

### System Maintenance
- **Regular Updates**: Monthly system updates
- **Backup Schedule**: Daily automated backups
- **Security Patches**: Applied as needed
- **Performance Monitoring**: Continuous monitoring

### Training & Documentation
- **User Training**: Available upon request
- **Video Tutorials**: Online training materials
- **User Manual**: Comprehensive documentation
- **FAQ Section**: Common questions and answers

---

## Conclusion

The Kabianga Annual Research Grants Portal represents a comprehensive solution for managing the entire research lifecycle at the University of Kabianga. With its robust features, user-friendly interface, and comprehensive reporting capabilities, the system streamlines research management and enhances collaboration between researchers, administrators, and supervisors.

### Key Benefits Achieved:
- **Efficiency**: Streamlined proposal and project management
- **Transparency**: Clear tracking and reporting
- **Collaboration**: Enhanced communication and coordination
- **Compliance**: Adherence to university policies and procedures
- **Analytics**: Data-driven decision making

### Future Enhancements:
- **Mobile Application**: Native mobile apps for iOS and Android
- **Advanced Analytics**: Machine learning-powered insights
- **Integration**: Connect with external research databases
- **Workflow Automation**: Enhanced automated processes
- **Multi-language Support**: Support for local languages

---

*This presentation document provides a comprehensive overview of the Kabianga ARG Portal. For specific technical details or additional information, please refer to the system documentation or contact the development team.*