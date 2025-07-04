<?php

use App\Http\Controllers\Admin\ActionUserController;
use App\Http\Controllers\Api\Auth\AppPlanController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\CheckController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\UpdateProfileController;
use App\Http\Controllers\Api\Investments\InvestmentController;
use App\Http\Controllers\Api\Loan\LoanController;
use App\Http\Controllers\Api\Public\StarkApiController;
use App\Http\Controllers\Api\Public\TelemedicalController;
use App\Http\Controllers\Api\Transactions\AgencyController;
use App\Http\Controllers\Api\Transactions\PixController;
use App\Http\Controllers\Api\Transactions\TransactionsController;
use App\Http\Controllers\Api\Users\BalanceController;
use App\Http\Controllers\Api\Users\ComunicationController;
use App\Http\Controllers\Api\Users\ContractController;
use App\Http\Middleware\StarckApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckApiToken;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\UpdateController;
use App\Http\Controllers\Api\Users\ActiveAccountController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes Auth
Route::post('login', [AuthController::class, 'login']);

//Route Register
Route::get('/validate-email/{email}', [CheckController::class, 'validateEmail']);
Route::post('/check-email', [CheckController::class, 'checkEmail']);
//Route::post('/register', [RegisterController::class, 'register']);
//Route::post('/updateUser', [UpdateController::class, 'updateUser']);

//Forgot Password
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

//Route Logout
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Protectd Routes
//User Data
Route::middleware('auth:api')->get('/userData', [AuthController::class, 'userData']);
Route::middleware('auth:api')->post('/checkout', [ActiveAccountController::class, 'checkout']);
Route::middleware('auth:api')->get('/user/checkAccount', [ActiveAccountController::class, 'checkAccount']);
Route::middleware('auth:api')->get('/user/status', [AuthController::class, 'status']);
//User Card
Route::middleware('auth:api')->get('/transactions', [TransactionsController::class, 'index']);

//Update Profile
Route::middleware('auth:api')->get('/getUserProfile', [ProfileController::class, 'getUserProfile']);
Route::middleware('auth:api')->post('/updateUserProfile', [ProfileController::class, 'updateUserProfile']);

//Transactions

//PIX recebimento
Route::middleware('auth:api')->post('/receive/qrcode/create', [TransactionsController::class, 'receive_qrcode_create']);
Route::middleware('auth:api')->get('/receive/qrcode/view/{id}', [TransactionsController::class, 'receive_qrcode_view']);
Route::middleware('auth:api')->get('/receive/qrcode/extract', [TransactionsController::class, 'receive_qrcode_extract']);

//PIX pagamento
Route::middleware('auth:api')->post('/payment/pix/preview', [TransactionsController::class, 'payment_pix_preview']);
Route::middleware('auth:api')->post('/payment/pix/copy-paste', [TransactionsController::class, 'payment_pix_copy_paste']);
Route::middleware('auth:api')->get('/payment/pix/extract', [TransactionsController::class, 'payment_pix_extract']);
Route::middleware('auth:api')->post('/payment/pix/key/preview', [TransactionsController::class, 'payment_pix_key_preview']);
Route::middleware('auth:api')->post('/payment/pix/key', [TransactionsController::class, 'payment_pix_key']);


//Comprovante
Route::middleware('auth:api')->get('/invoice/{id}', [TransactionsController::class, 'invoice']);
//Transferencia
Route::middleware('auth:api')->post('/transfer/accounts/preview', [TransactionsController::class, 'transfer_accounts_preview']);
Route::middleware('auth:api')->post('/transfer/accounts', [TransactionsController::class, 'transfer_accounts_store']);
Route::middleware('auth:api')->get('/transfer/extract', [TransactionsController::class, 'transfer_extract']);
//Pix Transction
Route::middleware('auth:api')->get('/type', [PixController::class, 'getTypes']);
//Agency User
Route::middleware('auth:api')->get('/userAgency', [AgencyController::class, 'getAgencyData']);

//Loan
Route::middleware('auth:sanctum')->get('loan', [LoanController::class, 'getLoan']);
Route::middleware('auth:sanctum')->get('ledingStatus', [LoanController::class, 'lendingStatus']);
Route::middleware('auth:sanctum')->post('/ledingRequest/{loan_id}', [LoanController::class, 'lendingRequest']);
Route::middleware('auth:sanctum')->post('/lendingRequestDocuments/{id}', [LoanController::class, 'lendingRequestDocuments']);

//Balance
Route::middleware('auth:sanctum')->get('/balance', [BalanceController::class, 'balance']);

//Stark Bank
Route::middleware(StarckApiToken::class)->get('/stark/send', [StarkApiController::class, 'send']);

//Publics APIS
Route::get('/plans', [AppPlanController::class, 'index']);


//Update User Password
Route::middleware('auth:sanctum')->post('/update/user/password', [ProfileController::class, 'updatePassword']);


//Update User Password
Route::middleware('auth:sanctum')->post('/update/user/profile', [ProfileController::class, 'updateUserProfile']);


//Get User Profile
Route::middleware('auth:sanctum')->get('/get/user/profile', [ProfileController::class, 'getUserProfile']);

//Get User Location
Route::middleware('auth:sanctum')->post('/save-location',[ActionUserController::class,'saveAction']);


//Contracts
Route::middleware('auth:sanctum')->get('/accountContract',[ContractController::class, 'getOpenContract']);
Route::middleware('auth:sanctum')->get('/allContracts',[ContractController::class, 'getAllContracts']);


//Messages
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/message',[ComunicationController::class, 'index']);
    Route::get('/message/{id}',[ComunicationController::class, 'show']);
});

