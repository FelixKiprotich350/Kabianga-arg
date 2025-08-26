<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\LoginController,
    Auth\RegisterController,
    DashboardController,
    UsersController,
    Proposals\ProposalsController,
    GrantsController,
    DepartmentsController,
    SchoolsController,
    ProjectsController,
    ReportsController,
    SupervisionController,
    ResearchThemeController,
    PermissionsController,
    SettingsController,
    Proposals\CollaboratorsController,
    Proposals\PublicationsController,
    Proposals\ExpendituresController,
    Proposals\WorkplanController,
    Proposals\ResearchdesignController,
    FinYearController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API Routes (No Authentication)
Route::prefix('v1')->group(function () {
    // Test endpoints
    Route::get('/test/connection', [\App\Http\Controllers\ApiTestController::class, 'testConnection']);
    
    // Auth check
    Route::get('/auth/check', [\App\Http\Controllers\Auth\AuthController::class, 'check']);
    
    // Authentication
    Route::post('/auth/login', [LoginController::class, 'apiLogin']);
    Route::post('/auth/register', [RegisterController::class, 'apiRegister']);
    Route::post('/auth/forgot-password', [RegisterController::class, 'apiForgotPassword']);
    Route::get('/auth/permission', [LoginController::class, 'subpermission']);
    
    // Public Data
    Route::get('/users', [UsersController::class, 'apiGetAllUsers']);
    Route::get('/users/{id}', [UsersController::class, 'apiGetUser']);
    Route::get('/themes', [ResearchThemeController::class, 'fetchAllThemes']);
    Route::get('/schools', [SchoolsController::class, 'fetchallschools']);
    Route::get('/departments', [DepartmentsController::class, 'fetchalldepartments']);
    Route::get('/grants', [GrantsController::class, 'fetchallgrants']);
});

// Protected API Routes (Authentication Required)
Route::prefix('v1')->middleware(['web', 'auth'])->group(function () {
    
    // Test endpoints (authenticated)
    Route::get('/test/users', [\App\Http\Controllers\ApiTestController::class, 'testUsers']);
    Route::get('/test/proposals', [\App\Http\Controllers\ApiTestController::class, 'testProposals']);
    
    // Authentication
    Route::post('/auth/logout', [LoginController::class, 'apiLogout']);
    Route::get('/auth/me', [LoginController::class, 'apiMe']);
    Route::get('/auth/permissions', [\App\Http\Controllers\Auth\AuthController::class, 'permissions']);
    Route::post('/auth/refresh', [LoginController::class, 'apiRefresh']);
    
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/charts', [DashboardController::class, 'chartdata']);
    Route::get('/dashboard/activity', [DashboardController::class, 'getRecentActivity']);
    
    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'fetchallusers']);
        Route::post('/', [UsersController::class, 'createUser']);
        Route::get('/search', [UsersController::class, 'fetchsearchusers']);
        Route::get('/{id}', [UsersController::class, 'apiGetUser']);
        Route::put('/{id}', [UsersController::class, 'updatebasicdetails']);
        Route::patch('/{id}/role', [UsersController::class, 'updaterole']);
        Route::patch('/{id}/permissions', [UsersController::class, 'updatePermissions']);
        Route::patch('/{id}/status', [UsersController::class, 'updateStatus']);
        Route::patch('/{id}/superadmin', [UsersController::class, 'updateSuperAdmin']);
        Route::patch('/{id}/enable', [UsersController::class, 'enableUser']);
        Route::patch('/{id}/disable', [UsersController::class, 'disableUser']);
        Route::post('/{id}/reset-password', [RegisterController::class, 'resetuserpassword']);
    });
    
    // Proposals Management
    Route::prefix('proposals')->group(function () {
        Route::get('/', [ProposalsController::class, 'fetchallproposals']);
        Route::post('/', [ProposalsController::class, 'postnewproposal']);
        Route::get('/my', [ProposalsController::class, 'fetchmyapplications']);
        Route::get('/search', [ProposalsController::class, 'fetchsearchproposals']);
        Route::get('/{id}', [ProposalsController::class, 'fetchsingleproposal']);
        Route::get('/{id}/view', [ProposalsController::class, 'getsingleproposalpage']);
        Route::put('/{id}/basic', [ProposalsController::class, 'updatebasicdetails']);
        Route::put('/{id}/research', [ProposalsController::class, 'updateresearchdetails']);
        Route::post('/{id}/submit', [ProposalsController::class, 'submitproposal']);
        Route::post('/{id}/receive', [ProposalsController::class, 'receiveproposal']);
        Route::post('/{id}/approve-reject', [ProposalsController::class, 'approverejectproposal']);
        Route::post('/{id}/approve', [ProposalsController::class, 'approveProposal']);
        Route::post('/{id}/reject', [ProposalsController::class, 'rejectProposal']);
        Route::post('/{id}/mark-draft', [ProposalsController::class, 'markAsDraft']);
        Route::post('/{id}/request-changes', [ProposalsController::class, 'requestChanges']);
        Route::patch('/{id}/edit-status', [ProposalsController::class, 'changeeditstatus']);
        Route::get('/{id}/status', [ProposalsController::class, 'fetchsubmissionstatus']);
        Route::get('/{id}/changes', [ProposalsController::class, 'fetchproposalchanges']);
        Route::get('/{id}/pdf', [ProposalsController::class, 'printpdf']);
        Route::get('/test-snappy', [ProposalsController::class, 'testSnappy']);
        
        // Proposal Components
        Route::get('/{id}/collaborators', [ProposalsController::class, 'fetchcollaborators']);
        Route::get('/{id}/publications', [ProposalsController::class, 'fetchpublications']);
        Route::get('/{id}/expenditures', [ProposalsController::class, 'fetchexpenditures']);
        Route::get('/{id}/workplans', [ProposalsController::class, 'fetchworkplanitems']);
        Route::get('/{id}/research-design', [ProposalsController::class, 'fetchresearchdesign']);
        Route::get('/{id}/budget-validation', [ProposalsController::class, 'budgetValidation']);
    });
    
    // Proposal Changes
    Route::prefix('proposal-changes')->group(function () {
        Route::post('/', [\App\Http\Controllers\Proposals\ProposalChangesController::class, 'postproposalchanges']);
        Route::get('/search', [\App\Http\Controllers\Proposals\ProposalChangesController::class, 'fetchsearch']);
        Route::get('/{id}', [\App\Http\Controllers\Proposals\ProposalChangesController::class, 'fetchall']);
    });
    
    // Collaborators
    Route::prefix('collaborators')->group(function () {
        Route::get('/', [CollaboratorsController::class, 'fetchall']);
        Route::post('/', [CollaboratorsController::class, 'postcollaborator']);
        Route::get('/search', [CollaboratorsController::class, 'fetchsearch']);
        Route::put('/{id}', [CollaboratorsController::class, 'updateCollaborator']);
        Route::delete('/{id}', [CollaboratorsController::class, 'deleteCollaborator']);
    });
    
    // Publications
    Route::prefix('publications')->group(function () {
        Route::get('/', [PublicationsController::class, 'fetchall']);
        Route::post('/', [PublicationsController::class, 'postpublication']);
        Route::get('/search', [PublicationsController::class, 'fetchsearch']);
        Route::put('/{id}', [PublicationsController::class, 'updatePublication']);
        Route::delete('/{id}', [PublicationsController::class, 'deletePublication']);
    });
    
    // Expenditures
    Route::prefix('expenditures')->group(function () {
        Route::get('/', [ExpendituresController::class, 'fetchall']);
        Route::post('/', [ExpendituresController::class, 'postexpenditure']);
        Route::get('/search', [ExpendituresController::class, 'fetchsearch']);
        Route::get('/budget-validation', [ExpendituresController::class, 'getBudgetValidation']);
        Route::put('/{id}', [ExpendituresController::class, 'updateExpenditure']);
        Route::delete('/{id}', [ExpendituresController::class, 'deleteExpenditure']);
    });
    
    // Workplans
    Route::prefix('workplans')->group(function () {
        Route::get('/', [WorkplanController::class, 'fetchall']);
        Route::post('/', [WorkplanController::class, 'postworkplanitem']);
        Route::get('/search', [WorkplanController::class, 'fetchsearch']);
        Route::put('/{id}', [WorkplanController::class, 'updateWorkplan']);
        Route::delete('/{id}', [WorkplanController::class, 'deleteWorkplan']);
    });
    
    // Research Design
    Route::prefix('research-design')->group(function () {
        Route::get('/', [ResearchdesignController::class, 'fetchall']);
        Route::post('/', [ResearchdesignController::class, 'postresearchdesignitem']);
        Route::get('/search', [ResearchdesignController::class, 'fetchsearch']);
        Route::put('/{id}', [ResearchdesignController::class, 'updateResearchDesign']);
        Route::delete('/{id}', [ResearchdesignController::class, 'deleteResearchDesign']);
    });
    
    // Projects Management
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectsController::class, 'fetchallprojects']);
        Route::get('/my', [ProjectsController::class, 'fetchmyallprojects']);
        Route::get('/active', [ProjectsController::class, 'fetchallactiveprojects']);
        Route::get('/my-active', [ProjectsController::class, 'fetchmyactiveprojects']);
        Route::get('/search', [ProjectsController::class, 'fetchsearchallprojects']);
        Route::get('/{id}', [ProjectsController::class, 'viewanyproject']);
        Route::post('/{id}/progress', [ProjectsController::class, 'submitmyprogress'])->name('api.projects.submitmyprogress');
        Route::get('/{id}/progress', [ProjectsController::class, 'fetchprojectprogress'])->name('api.projects.fetchprojectprogress');
        Route::post('/{id}/funding', [ProjectsController::class, 'addfunding'])->name('api.projects.addfunding');
        Route::get('/{id}/funding', [ProjectsController::class, 'fetchprojectfunding'])->name('api.projects.fetchprojectfunding');
        Route::patch('/{id}/pause', [ProjectsController::class, 'pauseproject'])->name('api.projects.pauseproject');
        Route::patch('/{id}/resume', [ProjectsController::class, 'resumeproject'])->name('api.projects.resumeproject');
        Route::patch('/{id}/cancel', [ProjectsController::class, 'cancelproject'])->name('api.projects.cancelproject');
        Route::patch('/{id}/complete', [ProjectsController::class, 'completeproject'])->name('api.projects.completeproject');
        Route::patch('/{id}/assign', [ProjectsController::class, 'assignme']);
    });
    
    // Monitoring/Supervision
    Route::prefix('monitoring')->group(function () {
        Route::get('/', [SupervisionController::class, 'home']);
        Route::get('/{id}', [SupervisionController::class, 'viewmonitoringpage']);
        Route::post('/{id}/report', [SupervisionController::class, 'addreport']);
        Route::get('/{id}/reports', [SupervisionController::class, 'fetchmonitoringreport']);
    });
    
    // Schools Management
    Route::prefix('schools')->group(function () {
        Route::get('/', [SchoolsController::class, 'fetchallschools']);
        Route::post('/', [SchoolsController::class, 'postnewschool']);
        Route::get('/search', [SchoolsController::class, 'fetchsearchschools']);
        Route::get('/{id}', [SchoolsController::class, 'getviewschoolpage']);
        Route::put('/{id}', [SchoolsController::class, 'updateschool'])->name('api.schools.updateschool');
    });
    
    // Departments Management
    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentsController::class, 'fetchalldepartments']);
        Route::get('/for-proposals', [DepartmentsController::class, 'fetchdepartmentsforproposals']);
        Route::post('/', [DepartmentsController::class, 'postnewdepartment']);
        Route::get('/{id}', [DepartmentsController::class, 'getviewdepartmentpage']);
        Route::put('/{id}', [DepartmentsController::class, 'updatedepartment']);
    });
    
    // Grants Management
    Route::prefix('grants')->group(function () {
        Route::get('/', [GrantsController::class, 'fetchallgrants']);
        Route::post('/', [GrantsController::class, 'postnewgrant']);
        Route::get('/search', [GrantsController::class, 'fetchsearchgrants']);
        Route::get('/{id}', [GrantsController::class, 'getviewsinglegrantpage']);
        Route::put('/{id}', [GrantsController::class, 'updategrant']);
    });
    
    // Financial Years
    Route::prefix('financial-years')->group(function () {
        Route::get('/', [FinYearController::class, 'fetchallfinyears']);
        Route::post('/', [FinYearController::class, 'postnewfinyear']);
    });
    

    
    // Research Themes
    Route::prefix('themes')->group(function () {
        Route::get('/', [ResearchThemeController::class, 'fetchAllThemes']);
        Route::post('/', [ResearchThemeController::class, 'createTheme']);
        Route::put('/{id}', [ResearchThemeController::class, 'updateTheme']);
        Route::delete('/{id}', [ResearchThemeController::class, 'deleteTheme']);
    });
    
    // Permissions
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionsController::class, 'fetchAllPermissions']);
        Route::get('/role/{role}', [PermissionsController::class, 'fetchPermissionsByRole']);
    });
    
    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'fetchAllSettings']);
        Route::put('/', [SettingsController::class, 'updateSettings']);
        Route::post('/current-grant', [GrantsController::class, 'postcurrentgrant']);
        Route::post('/current-year', [GrantsController::class, 'postcurrentfinyear']);
    });
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/summary', [ReportsController::class, 'getReportsSummary']);
        Route::get('/proposals', [ReportsController::class, 'getallproposals']);
        Route::get('/proposals/by-school', [ReportsController::class, 'getProposalsBySchool']);
        Route::get('/proposals/by-theme', [ReportsController::class, 'getProposalsByTheme']);
        Route::get('/proposals/by-grant', [ReportsController::class, 'getProposalsByGrant']);
        Route::get('/financial', [ReportsController::class, 'getFinancialSummary']);
        Route::get('/projects', [ReportsController::class, 'getProjectsReport']);
        Route::get('/users', [ReportsController::class, 'getUserActivityReport']);
        Route::get('/publications', [ReportsController::class, 'getPublicationsReport']);
        Route::get('/progress', [ReportsController::class, 'getProgressReport']);
        Route::get('/compliance', [ReportsController::class, 'getComplianceReport']);
        Route::get('/performance', [ReportsController::class, 'getPerformanceReport']);
        Route::get('/budget-actual', [ReportsController::class, 'getBudgetActualReport']);
        Route::post('/export', [ReportsController::class, 'exportReport']);
    });
    
    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/types', [UsersController::class, 'fetchallnotificationtypes']);
        Route::get('/types/{id}/users', [UsersController::class, 'fetchtypewiseusers']);
        Route::post('/types/{id}/users', [UsersController::class, 'addnotifiableusers']);
        Route::delete('/types/{id}/users', [UsersController::class, 'removenotifiableuser']);
    });
});


