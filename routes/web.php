<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CommonPagesController,
    Auth\LoginController,
    Auth\RegisterController,
    Auth\LogoutController,
    DashboardController,
    MyProfileController,
    Proposals\ProposalsController,
    GrantsController,
    UsersController, 
    Proposals\CollaboratorsController,
    Proposals\PublicationsController,
    Proposals\ExpendituresController,
    Proposals\WorkplanController,
    Proposals\ResearchdesignController,
    ReportsController,
    Proposals\ProposalChangesController,
    DepartmentsController,
    SchoolsController,
    Auth\CustomPasswordResetController,
    Auth\CustomVerificationController,
    ProjectsController,
    SupervisionController,
    FinYearController,
    ResearchThemeController,
    PermissionsController,
    SettingsController,
};


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/setupadmin', [CommonPagesController::class, 'setupadmin'])->name('pages.setupadmin');
Route::post('/setupadmin', [CommonPagesController::class, 'makeInitialAdmin'])->name('api.makeinitialadmin');

//custom password reset
Route::get('password/reset', [CustomPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [CustomPasswordResetController::class, 'sendResetLinkEmail'])->name('password.requestreset');
Route::get('password/reset/{token}', [CustomPasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [CustomPasswordResetController::class, 'reset'])->name('password.update');



// Authentication Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('pages.login');
Route::get('/index', [LoginController::class, 'showLoginForm'])->name('pages.login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('pages.login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('pages.register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::match(['post', 'get'], '/logout', [LogoutController::class, 'logout'])->name('route.logout');


// Protected Routes
//custom account verification
// Email verification routes (auth only, no email verification required)
Route::middleware('auth')->group(function () {
    Route::get('email/verify', [CustomVerificationController::class, 'show'])->name('pages.account.verifyemail');
    Route::post('email/resend', [CustomVerificationController::class, 'resend'])->name('verification.resend');
});
Route::get('email/verify/{id}/{hash}', [CustomVerificationController::class, 'verify'])->name('verification.verify');

// Protected routes (require authentication and email verification)
Route::group(['middleware' => ['auth', 'verified']], function () {

    //Unauthorized
    Route::get('/unauthorized', [DashboardController::class, 'unauthorized'])->name('pages.unauthorized');

    //dashboard & home
    Route::get('/home', [DashboardController::class, 'modernHome'])->name('pages.home');
    Route::get('/dashboard', [DashboardController::class, 'modernDashboard'])->name('pages.dashboard');


    //proposals
    Route::get('/proposals', [ProposalsController::class, 'index'])->name('pages.proposals.index');
    Route::get('/proposals/newproposal', [ProposalsController::class, 'modernNewProposal'])->name('pages.proposals.viewnewproposal');

    Route::get('/proposals/view/{id}', [ProposalsController::class, 'getsingleproposalpage'])->name('pages.proposals.viewproposal');



    Route::get('/proposals/edit/{id}', [ProposalsController::class, 'geteditsingleproposalpage'])->name('pages.proposals.editproposal');





    //schools
    Route::get('/schools/home', [SchoolsController::class, 'modernViewAllSchools'])->name('pages.schools.home');
    Route::get('/schools', [SchoolsController::class, 'modernViewAllSchools'])->name('pages.schools.home');
    Route::get('/schools/view/{id}', [SchoolsController::class, 'getviewschoolpage'])->name('pages.schools.viewschool');
    Route::get('/schools/edit/{id}', [SchoolsController::class, 'geteditschoolpage'])->name('pages.schools.editschool');

    //departments
    Route::get('/departments', function () {
        return redirect()->route('pages.schools.home');
    });
    Route::get('/departments/view/{id}', [DepartmentsController::class, 'getviewdepartmentpage'])->name('pages.departments.viewdepartment');
    Route::get('/departments/edit/{id}', [DepartmentsController::class, 'geteditdepartmentpage'])->name('pages.departments.editdepartment');


    //grants
    Route::get('/grants/home', [GrantsController::class, 'viewallgrants'])->name('pages.grants.home');
    Route::get('/grants/view/{id}', [GrantsController::class, 'getviewsinglegrantpage'])->name('pages.grants.viewgrant');
    Route::get('/grants/edit/{id}', [GrantsController::class, 'geteditsinglegrantpage'])->name('pages.grants.editgrant');

    //financial years
    Route::get('/financial-years', [FinYearController::class, 'index'])->name('pages.finyears.index');

    //users
    Route::get('/users/manage', [UsersController::class, 'viewallusers'])->name('pages.users.manage');
    Route::get('/users', [UsersController::class, 'viewallusers'])->name('users.index');
    Route::get('/users/{id}', [UsersController::class, 'viewsingleuser'])->name('pages.users.viewsingleuser');
    Route::get('/users/{id}/permissions', [UsersController::class, 'showPermissions'])->name('users.permissions');


    //notificationtypes
    Route::get('/notificationtype/view/{id}', [UsersController::class, 'managenotificationtype'])->name('pages.notificationtype.managenotificationtype');


    //collaborators
    Route::get('/collaborators/edit/{id}', [CollaboratorsController::class, 'geteditsingleuserpage'])->name('pages.collaborators.editcollaborator');

    //publications - page routes only
    Route::get('/publications/edit/{id}', [PublicationsController::class, 'geteditsinglepublicationpage'])->name('pages.publications.editpublication');

    //expenditures - page routes only
    Route::get('/expenditures/edit/{id}', [ExpendituresController::class, 'geteditsingleexpenditurepage'])->name('pages.expenditures.editexpenditures');

    //workplanitems - page routes only
    Route::get('/workplan/edit/{id}', [WorkplanController::class, 'geteditsingleexpenditurepage'])->name('pages.expenditures.editexpenditures');

    //researchdesignitems - page routes only
    Route::get('/researchdesign/edit/{id}', [ResearchdesignController::class, 'geteditsingleexpenditurepage'])->name('pages.researchdesign.editexpenditures');


    //projects
    Route::get('/projects', [ProjectsController::class, 'index'])->name('pages.projects.index');

    Route::get('/projects/myprojects/{id}', [ProjectsController::class, 'viewmyproject'])->name('pages.projects.viewmyproject');
    Route::get('/projects/allprojects/{id}', [ProjectsController::class, 'viewanyproject'])->name('pages.projects.viewanyproject');

    //monitoring
    Route::get('/monitoring/home', [SupervisionController::class, 'home'])->name('pages.monitoring.home');
    Route::get('/monitoring/project/{id}', [SupervisionController::class, 'viewmonitoringpage'])->name('pages.monitoring.project');

    //profile
    Route::get('/myprofile', [MyProfileController::class, 'myprofile'])->name('pages.myprofile');



    //reports
    Route::get('/reports/home', [ReportsController::class, 'home'])->name('pages.reports.home');
    Route::get('/reports/financial', function() {
        if (!auth()->user()->haspermission('canviewreports')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to View Reports!");
        }
        $allgrants = \App\Models\Grant::all();
        $allfinyears = \App\Models\FinancialYear::all();
        return view('pages.reports.financial-dashboard', compact('allgrants', 'allfinyears'));
    })->name('pages.reports.financial');
    Route::get('/reports/advanced', function() {
        if (!auth()->user()->haspermission('canviewreports')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to View Reports!");
        }
        $allgrants = \App\Models\Grant::all();
        return view('pages.reports.advanced-reports', compact('allgrants'));
    })->name('pages.reports.advanced');

    //themes
    Route::get('/themes', [ResearchThemeController::class, 'index'])->name('pages.themes.index');

    //notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    //funding
    Route::post('/projects/{id}/funding', [\App\Http\Controllers\FinancesController::class, 'addFunding'])->name('projects.addFunding');
    


});
;