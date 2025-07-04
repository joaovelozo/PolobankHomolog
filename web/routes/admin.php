<?php
// Admin routes

use App\Http\Controllers\Admin\ActionUserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminScoreController;
use App\Http\Controllers\Admin\AdminTelemedicineController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ComunicationController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ContractsController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SplitController;
use App\Http\Controllers\Admin\TelemedicineFormController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BalanceController;
use App\Http\Controllers\Admin\CreditController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DocumentsController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\StaController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TransactionsController;
use App\Http\Controllers\Admin\PinController;
use App\Http\Controllers\Admin\AdminInvestmentController;
use App\Http\Controllers\Admin\PlanController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

//Balance
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/add-balance', [BalanceController::class, 'addBalance'])->name('admin.add-balance');
    Route::post('/admin/add-balance', [BalanceController::class, 'store']);
});

//Debit
Route::get('/admin/debit', [BalanceController::class, 'debit'])->name('admin.debit');
Route::post('/admin/debit', [BalanceController::class, 'debitStore']);

//STA
Route::get('/admin/generate-sta', [StaController::class, 'staGenerate'])->name('admin.generate-sta');

//Agencias
Route::resource('/admin/agency', AgencyController::class);
Route::post('/admin/agency/clients/data/{id}', [AgencyController::class, 'getClients'])->name('admin.agency.getClients');

Route::get('/admin/agency/clients/{id}', [AgencyController::class, 'clientAgency'])->name('admin.agency.clients');
//Clients By Agency
Route::resource('/customer', CustomerController::class);

Route::put('/customer/{id}/block', [CustomerController::class, 'block'])->name('customer.block');
Route::put('/customer/{id}/unblock', [CustomerController::class, 'unblock'])->name('customer.unblock');

//User Transaction
Route::get('/admin/user/transaction/{id}', [CustomerController::class, 'userAgencyTransaction'])->name('admin.user.transaction');

//Gerentes
Route::resource('/admin/manager', ManagerController::class);

//Profile
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');


    //Admin Update  Password
    Route::get('/admin/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'adminUpdatePassword'])->name('update.password');


    //Credit
    Route::resource('/admin/loan', LoanController::class);
    Route::get('/admin/lending', [LoanController::class, 'lending'])->name('admin.lending');
    Route::get('lendings/{id}', [LoanController::class, 'showLending'])->name('loan.show_lending');
    Route::post('lendings/{id}/respond', [LoanController::class, 'respondToLending'])->name('loan.respond_to_lending');
    Route::get('lending/user/response/{id}', [LoanController::class, 'responseUser'])->name('lending.user.response');
    Route::post('lending/user/store', [LoanController::class, 'storeUserResponse'])->name('lending.user.store');
    Route::delete('/lending/{id}', [LoanController::class, 'deleteLending'])->name('lending.delete');
    //View Documents
    Route::get('/show/documents/{id}', [LoanController::class, 'showDocuments'])->name('show.documents');

    //Messages
    Route::resource('/admin/message', MessageController::class);

    //Service
    Route::resource('/admin/service', ServiceController::class);

    //Permission
    Route::resource('/admin/permission', PermissionController::class);

    //Roles
    Route::resource('/admin/role', RoleController::class);


    //Index Role in Permission
    Route::get('/role/permission/index', [RoleController::class, 'rolePermissionIndex'])->name('role.permission.index');

    //Add Role in Permission
    Route::get('/add/roles/permission', [RoleController::class, 'addRolesPermission'])->name('add.roles.permission');

    //Role Permission Store
    Route::post('/role/permission/store', [RoleController::class, 'rolePermissionStore'])->name('role.permission.store');

    //Edit Permission Role
    Route::get('/admin/edit/roles/{id}', [RoleController::class, 'editRoles'])->name('admin.edit.roles');


    //Update Permission
    Route::post('/admin/roles/update/{id}', [RoleController::class, 'rolesUpdate'])->name('admin.roles.update');


    //Delete Permission
    Route::get('/admin/delete/roles/{id}', [RoleController::class, 'deleteRole'])->name('admin.delete.roles');

    //Admins
    Route::get('/all/admin', [AdminController::class, 'allAdmins'])->name('all.admin');
    Route::get('/add/admin', [AdminController::class, 'addAdmin'])->name('add.admin');
    Route::post('/admin/user/store', [AdminController::class, 'adminUserStore'])->name('admin.user.store');
    Route::get('/edit/admin/role/{id}', [AdminController::class, 'editAdminRole'])->name('edit.admin.role');
    Route::post('/admin/user/update/{id}', [AdminController::class, 'adminUserUpdate'])->name('admin.user.update');
    Route::get('/delete/admin/role/{id}', [AdminController::class, 'deleteAdminRole'])->name('delete.admin.role');

    //Documents
    Route::resource('/docs', DocumentsController::class);

    //Transactions all Users By Agency
    Route::resource('/transactions', TransactionsController::class);

    //Message Agency
    Route::resource('/agencycom', ComunicationController::class);

    //Internal Score
    Route::resource('/adminscore', AdminScoreController::class);

    //Types
    Route::resource('/types', TypeController::class);

    //Tickets
    Route::resource('/ticketsadmin', TicketController::class);
    Route::get('/ticketsadmin/{id}/create', [TicketController::class, 'create'])->name('ticketsadmin.create');
    Route::post('/ticketsadmin/{id}', [TicketController::class, 'store'])->name('ticketsadmin.store');
    //Contratcs
    Route::resource('/contracts', ContractsController::class);

    //Pin
    Route::resource('/pinadmin',PinController::class);

    //Admin Add Investment to Users
    //Clients
    Route::resource('/clients',ClientController::class);

    //News
    Route::resource('/news',NewsController::class);

    //Contact
    Route::resource('/contact',ContactController::class);

    //Payment
    Route::resource('/adpayment',SplitController::class);
    Route::get('/users/search', [SplitController::class, 'search'])->name('users.search');

    //Group Investment
    Route::resource('/groups', GroupController::class);

    //Actions User Controller
    Route::get('/useraction',[ActionUserController::class, 'index'])->name('actions.user');

});
