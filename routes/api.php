<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\FinYearController;
use App\Http\Controllers\GrantsController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\Proposals\CollaboratorsController;
use App\Http\Controllers\Proposals\ExpendituresController;
use App\Http\Controllers\Proposals\ProposalsController;
use App\Http\Controllers\Proposals\PublicationsController;
use App\Http\Controllers\Proposals\ResearchdesignController;
use App\Http\Controllers\Proposals\WorkplanController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResearchThemeController;
use App\Http\Controllers\SchoolsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupervisionController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API Routes (No Authentication)
Route::prefix('v1')->group(function () {

    // Auth check
    Route::get('/auth/check', [\App\Http\Controllers\Auth\AuthController::class, 'check']);

    // Authentication
    Route::post('/auth/login', [LoginController::class, 'apiLogin']);
    Route::post('/auth/register', [RegisterController::class, 'apiRegister']);
    Route::post('/auth/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
    Route::post('/auth/password/validate', [PasswordResetController::class, 'validateToken']);
    Route::post('/auth/password/reset', [PasswordResetController::class, 'reset']);
    Route::get('/auth/permission', [LoginController::class, 'subpermission']);

    // Reference Data
    Route::get('/themes', [ResearchThemeController::class, 'fetchAllThemes']);
    Route::get('/schools', [SchoolsController::class, 'fetchallschools']);
    Route::get('/departments', [DepartmentsController::class, 'fetchalldepartments']);
    Route::get('/grants', [GrantsController::class, 'fetchallgrants']);
    Route::get('/financial-years', action: [FinYearController::class, 'fetchallfinyears']);
    Route::get('/expendituretypes', [\App\Http\Controllers\ExpenditureTypesController::class, 'fetchallexpendituretypes']);
    Route::get('/proposal-types', function() {
        return response()->json([
            'success' => true,
            'data' => [
                ['value' => 'research', 'label' => 'Research'],
                ['value' => 'innovation', 'label' => 'Innovation']
            ]
        ]);
    });

});

// Protected API Routes (Authentication Required)
Route::prefix('v1')->middleware(['auth:api'])->group(function () {

    // Authentication
    Route::post('/auth/logout', [LoginController::class, 'apiLogout']);
    Route::get('/auth/me', [LoginController::class, 'apiMe']);
    Route::get('/auth/permissions', [\App\Http\Controllers\Auth\AuthController::class, 'permissions']);
    Route::post('/auth/refresh', [LoginController::class, 'apiRefresh']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/charts', [DashboardController::class, 'chartdata']);

    // User Management
    Route::prefix('users')->group(function () {
        // Route::get('/users-r', [UsersController::class, 'apiGetAllUsers']);
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

        Route::get('/{id}', [ProposalsController::class, 'fetchsingleproposal']);
        Route::get('/{id}/view', [ProposalsController::class, 'getsingleproposalpage']);
        Route::put('/{id}/basic', [ProposalsController::class, 'updatebasicdetails']);
        Route::put('/{id}/research', [ProposalsController::class, 'updateresearchdetails']);
        Route::post('/{id}/submit', [ProposalsController::class, 'submitproposal']);
        Route::post('/{id}/receive', [ProposalsController::class, 'receiveproposal']);
        Route::patch('/{id}/approve', [ProposalsController::class, 'approveProposal']);
        Route::patch('/{id}/reject', [ProposalsController::class, 'rejectProposal']);
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
        
        // Proposal Reviewers
        Route::post('/{id}/reviewers', [\App\Http\Controllers\Proposals\ProposalReviewersController::class, 'assignReviewers']);
        Route::get('/{id}/reviewers', [\App\Http\Controllers\Proposals\ProposalReviewersController::class, 'getReviewers']);
        Route::delete('/{id}/reviewers/{reviewerId}', [\App\Http\Controllers\Proposals\ProposalReviewersController::class, 'removeReviewer']);
    });

    // My Review Proposals
    Route::get('/my-reviews', [\App\Http\Controllers\Proposals\ProposalReviewersController::class, 'getMyReviewProposals']);
    
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

    // Expenditure Types
    Route::prefix('expendituretypes')->group(function () {
        Route::post('/', [\App\Http\Controllers\ExpenditureTypesController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\ExpenditureTypesController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\ExpenditureTypesController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\ExpenditureTypesController::class, 'destroy']);
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
        Route::get('/active', [ProjectsController::class, 'fetchallactiveprojects']);
        Route::get('/{id}', [ProjectsController::class, 'viewprojectdetails']);
        Route::post('/{id}/progress', [ProjectsController::class, 'submitmyprogress'])->name('api.projects.submitmyprogress');
        Route::get('/{id}/progress', [ProjectsController::class, 'fetchprojectprogress'])->name('api.projects.fetchprojectprogress');
        Route::post('/{id}/funding', [ProjectsController::class, 'addfundingrequest'])->name('api.projects.addfundingrequest');
        Route::get('/{id}/funding', [ProjectsController::class, 'fetchprojectfunding'])->name('api.projects.fetchprojectfunding');
        Route::patch('/{id}/pause', [ProjectsController::class, 'pauseproject'])->name('api.projects.pauseproject');
        Route::patch('/{id}/resume', [ProjectsController::class, 'resumeproject'])->name('api.projects.resumeproject');
        Route::patch('/{id}/cancel', [ProjectsController::class, 'cancelproject'])->name('api.projects.cancelproject');
        Route::patch('/{id}/complete', [ProjectsController::class, 'completeproject'])->name('api.projects.completeproject');
        Route::patch('/{id}/assign', [ProjectsController::class, 'assignme']);
        Route::patch('/{id}/commission', [ProjectsController::class, 'commissionproject']);
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
        Route::post('/', [SchoolsController::class, 'postnewschool']);
        Route::get('/search', [SchoolsController::class, 'fetchsearchschools']);
        Route::get('/{id}', [SchoolsController::class, 'getviewschoolpage']);
        Route::put('/{id}', [SchoolsController::class, 'updateschool'])->name('api.schools.updateschool');
    });

    // Departments Management
    Route::prefix('departments')->group(function () {
        Route::get('/for-proposals', [DepartmentsController::class, 'fetchdepartmentsforproposals']);
        Route::post('/', [DepartmentsController::class, 'postnewdepartment']);
        Route::get('/{id}', [DepartmentsController::class, 'getviewdepartmentpage']);
        Route::put('/{id}', [DepartmentsController::class, 'updatedepartment']);
    });

    // Grants Management
    Route::prefix('grants')->group(function () {
        Route::post('/', [GrantsController::class, 'postnewgrant']);
        Route::get('/search', [GrantsController::class, 'fetchsearchgrants']);
        Route::get('/{id}', [GrantsController::class, 'getviewsinglegrantpage']);
        Route::put('/{id}', [GrantsController::class, 'updategrant']);
    });

    // Financial Years
    Route::prefix('financial-years')->group(function () {
        Route::post('/', [FinYearController::class, 'postnewfinyear']);
    });

    // Research Themes
    Route::prefix('themes')->group(function () {
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
        Route::get('/summary', [ReportsController::class, 'getSummaryReport']);
        Route::get('/proposals', [ReportsController::class, 'getallproposals']);
        Route::get('/proposals/by-school', [ReportsController::class, 'getProposalsBySchool']);
        Route::get('/proposals/by-theme', [ReportsController::class, 'getProposalsByTheme']);
        Route::get('/projects', [ReportsController::class, 'getProjectsReport']);
        Route::get('/users', [ReportsController::class, 'getUsersReport']);
        Route::get('/themes', [ReportsController::class, 'getThemesReport']);
        Route::get('/grants', [ReportsController::class, 'getGrantsReport']);
        Route::get('/schools', [ReportsController::class, 'getSchoolsReport']);
        Route::get('/departments', [ReportsController::class, 'getDepartmentsReport']);
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/types', [UsersController::class, 'fetchallnotificationtypes']);
        Route::get('/types/{id}/users', [UsersController::class, 'fetchtypewiseusers']);
        Route::post('/types/{id}/users', [UsersController::class, 'addnotifiableusers']);
        Route::delete('/types/{id}/users', [UsersController::class, 'removenotifiableuser']);
    });

    // Finances Management
    Route::prefix('finances')->group(function () {
        Route::patch('/funding/{fundingId}/approve', [\App\Http\Controllers\FinancesController::class, 'approveFundingRequest']);
        Route::get('/summary', [\App\Http\Controllers\FinancesController::class, 'getFinanceSummary']);
        Route::get('/pending-requests', [\App\Http\Controllers\FinancesController::class, 'getAllRequests']);
        Route::get('/budget-allocations', [\App\Http\Controllers\FinancesController::class, 'getBudgetAllocation']);
    });
});
    // Proposal Types endpoint
    Route::get('/proposal-types', function() {
        return response()->json([
            'success' => true,
            'data' => [
                ['value' => 'research', 'label' => 'Research'],
                ['value' => 'innovation', 'label' => 'Innovation']
            ]
        ]);
    });