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
    UserRoleController,
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
Route::get('/permission', [LoginController::class, 'subpermission'])->name('route.permission');

// Protected Routes
//custom account verification
// Email verification routes (no middleware for now)
Route::get('email/verify', [CustomVerificationController::class, 'show'])->name('pages.account.verifyemail');
Route::get('email/verify/{id}/{hash}', [CustomVerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [CustomVerificationController::class, 'resend'])->name('verification.resend');

// Protected routes
Route::group(['middleware' => 'auth'], function () {

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
    
    // Legacy routes for forms that haven't been updated to use API yet
    Route::post('/proposals/post', [ProposalsController::class, 'postnewproposal'])->name('route.proposals.post');
    Route::post('/proposals/updatebasicdetails/{id}', [ProposalsController::class, 'updatebasicdetails'])->name('route.proposals.updatebasicdetails');
    Route::post('/proposals/updateresearch/{id}', [ProposalsController::class, 'updateresearchdetails'])->name('route.proposals.updateresearchdetails');



//schools
    Route::get('/schools/home', [SchoolsController::class, 'modernViewAllSchools'])->name('pages.schools.home');
    Route::get('/schools', [SchoolsController::class, 'modernViewAllSchools'])->name('pages.schools.home');
    Route::get('/schools/view/{id}', [SchoolsController::class, 'getviewschoolpage'])->name('pages.schools.viewschool');
    Route::get('/schools/edit/{id}', [SchoolsController::class, 'geteditschoolpage'])->name('pages.schools.editschool');

    //departments
    Route::get('/departments', function() { return redirect()->route('pages.schools.home'); });
    Route::get('/departments/view/{id}', [DepartmentsController::class, 'getviewdepartmentpage'])->name('pages.departments.viewdepartment');
    Route::get('/departments/edit/{id}', [DepartmentsController::class, 'geteditdepartmentpage'])->name('pages.departments.editdepartment');


    //grants
    Route::get('/grants/home', [GrantsController::class, 'viewallgrants'])->name('pages.grants.home');
    Route::get('/grants/view/{id}', [GrantsController::class, 'getviewsinglegrantpage'])->name('pages.grants.viewgrant');
    Route::get('/grants/edit/{id}', [GrantsController::class, 'geteditsinglegrantpage'])->name('pages.grants.editgrant');




    //  Route::get('/grants/fetchsearchgrants', [GrantsController::class, 'fetchsearchgrants'])->name('api.grants.fetchsearchgrants');
    //  Route::get('/grants/view/{id}', [GrantsController::class, 'getviewsinglegrantpage'])->name('pages.grants.viewgrant');
    //  Route::get('/grants/edit/{id}', [GrantsController::class, 'geteditsinglegrantpage'])->name('pages.grants.editgrant');
    //  Route::post('/grants/edit/{id}', [GrantsController::class, 'updategrant'])->name('api.grants.updategrant');

    //users
    Route::get('/users/manage', [UsersController::class, 'viewallusers'])->name('pages.users.manage');
    Route::get('/users', [UsersController::class, 'viewallusers'])->name('users.index');
    Route::get('/users/{id}', [UsersController::class, 'viewsingleuser'])->name('pages.users.viewsingleuser');
    Route::get('/users/{id}/permissions', [UsersController::class, 'showPermissions'])->name('users.permissions');


    //notificationtypes
    Route::get('/notificationtype/view/{id}', [UsersController::class, 'managenotificationtype'])->name('pages.notificationtype.managenotificationtype');


    //collaborators
    Route::get('/collaborators/edit/{id}', [CollaboratorsController::class, 'geteditsingleuserpage'])->name('pages.collaborators.editcollaborator');

    //publications
    Route::get('/publications/edit/{id}', [PublicationsController::class, 'geteditsinglepublicationpage'])->name('pages.publications.editpublication');


    //expenditures
    Route::get('/expenditures/edit/{id}', [ExpendituresController::class, 'geteditsingleexpenditurepage'])->name('pages.expenditures.editexpenditures');

    //workplanitems
    Route::get('/workplan/edit/{id}', [WorkplanController::class, 'geteditsingleexpenditurepage'])->name('pages.expenditures.editexpenditures');


    //researchdesignitems
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


});;