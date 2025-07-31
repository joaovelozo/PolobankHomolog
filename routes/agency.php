<?php
use App\Http\Controllers\Agency\AgencyPixController;
use App\Http\Controllers\Agency\AgencyComunicationController;
use App\Http\Controllers\Agency\AgencyController;
use App\Http\Controllers\Agency\AgencyManagerController;
use App\Http\Controllers\Agency\AgencyDebitController;
use App\Http\Controllers\Agency\AgencyLoanController;
use App\Http\Controllers\Agency\AgencyPaymentController;
use App\Http\Controllers\Agency\AgencyReplyController;
use App\Http\Controllers\Agency\AgencyTicketController;
use App\Http\Controllers\Agency\AgencyTransferController;
use App\Http\Controllers\Agency\CardController;
use App\Http\Controllers\Agency\ClientController;
use App\Http\Controllers\Agency\ScoreController;
use App\Http\Controllers\Agency\UserClientController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Services\StarbankService;
use App\Http\Controllers\Agency\AgencyInvoiceController;
use App\Http\Controllers\Agency\ManagerKeyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Agency
Route::get('/agency/login', [AgencyController::class, 'AgencyLogin'])->name('agency.login');

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/agency/dashboard', [AgencyController::class, 'Dashboard'])->name('agency/dashboard');
    Route::get('/agency/link', [AgencyController::class, 'Link'])->name('agency/link');
    //Users Transactions
    Route::get('/agency/users/transactions', [AgencyController::class, 'usersAgencyTransactions'])->name('agency.users.transactions');
    //User Transaction
    Route::get('/agency/user/transaction/{id}', [AgencyController::class, 'userAgencyTransaction'])->name('agency.user.transaction');
    //Users
    Route::post('/agency/clients/data', [ClientController::class, 'getClients'])->name('clients.getClients');
    Route::resource('/agency/clients', ClientController::class);
    Route::put('/agency/clients/{id}/block', [ClientController::class, 'block'])->name('clients.block');
    Route::put('/agency/clients/{id}/unblock', [ClientController::class, 'unblock'])->name('clients.unblock');
    //Manager Profile Editing
    Route::get('/manager/profile', [UserController::class, 'managerProfile'])->name('manager.profile');
    Route::post('/manager/profile/store', [UserController::class, 'managerProfileStore'])->name('manager.profile.store');
    //Manager Update Password
    Route::get('/manager/change/password', [UserController::class, 'managerChangePassword'])->name('manager.change.password');
    Route::post('/manager/update/password', [UserController::class, 'managerUpdatePassword'])->name('manager.update.password');
    //Agency Comunication
    Route::resource('/agencycomunication', AgencyComunicationController::class);
    //Agency Loan
    Route::resource('/agencylending', AgencyLoanController::class);
    //Agency Client Debit
    //Debit
    Route::resource('agencydebit', AgencyDebitController::class);
    Route::get('/agency/debit', [AgencyDebitController::class, 'debit'])->name('agency.debit');
    Route::post('/agency/debit', [AgencyDebitController::class, 'debitStore']);
    Route::get('/agency/users/search', [AgencyDebitController::class, 'search'])->name('agency.users.search');
    Route::get('/agency/invoice/{id}/show', [AgencyInvoiceController::class, 'show'])->name('agency.invoice.show');

    //Card
    Route::resource('/agencycard', CardController::class);
    //Score
    Route::resource('/score', ScoreController::class);
    //Ticket
    Route::resource('/agencyticket', AgencyTicketController::class);
    //Response
    Route::resource('/agencyreply', AgencyReplyController::class);
    Route::get('/agencyreply/{id}/create', [AgencyReplyController::class, 'create'])->name('agencyreply.create');
    Route::post('/agencyreply/{id}', [AgencyReplyController::class, 'store'])->name('agencyreply.store');
    //Manager
    Route::get('/agency/dashboard', [AgencyManagerController::class, 'dashboard'])->name('agency.dashboard');

    //PIX
    Route::get('/agency/pix', [AgencyPaymentController::class, 'index'])->name('agency.pix.index');
     Route::get('/agency/agencypreview', [AgencyPaymentController::class, 'pixPreview'])->name('payment.agencypreview');
    Route::post('/agency/agencypreview', [AgencyPaymentController::class, 'pixPreview'])->name('payment.agencypreview');
    Route::get('/agency/payment/pix', [AgencyPaymentController::class, 'pix'])->name('agency.payment.pix');
    Route::post('/agency/payment/pix/create', [AgencyPaymentController::class, 'storePix'])->name('agency.payment.pix.store');
    Route::get('/agency/payment/pix/extract', [AgencyPaymentController::class, 'pix_extract'])->name('agency.payment.pix.extract');
    Route::get('/agency/payment/transfer/pix', [AgencyPaymentController::class, 'pix_transfer'])->name('agency.transfer.pix');
    Route::post('/agency/payment/transfer/pix', [AgencyPaymentController::class, 'store_pix_transfer'])->name('agency.transfer.pix');

    Route::get('/agency/qrcode', [AgencyPixController::class, 'qrcode'])->name('agency.qrcode');
    Route::post('/agency/qrcode/create', [AgencyPixController::class, 'create_qrcode'])->name('agency.qrcode.store');
    Route::get('/agency/qrcode/extract', [AgencyPixController::class, 'pix_qrcode_extract'])->name('agency.qrcode.extract');
    Route::get('/agency/qrcode/{id}', [AgencyPixController::class, 'view_qrcode'])->name('agency.qrcode.view');

    //TRANSFER
    Route::get('/agency/transfer', [AgencyTransferController::class, 'transferIndex'])->name('agency.transfer');
    Route::get('/agency/transfer/accounts', [AgencyTransferController::class, 'transfer_accounts'])->name('agency.transfer.accounts');
    Route::post('/agency/transfer/accounts', [AgencyTransferController::class, 'transfer_accounts_store'])->name('agency.transfer.accounts.store');
    Route::get('/agency/transfer/extract', [AgencyTransferController::class, 'transfer_extract'])->name('agency.transfer.extract');

    //Users
    Route::resource('/clientusers',UserClientController::class);

    //Manager Key
    Route::resource('/mkey', ManagerKeyController::class);
});


