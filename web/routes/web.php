<?php

use App\Http\Controllers\Admin\BalanceController;
use App\Http\Controllers\Auth\BusinessController;
use App\Http\Controllers\Transactions\TokenController;
set_time_limit(120); // Define 120 segundos como tempo limite de execução

use App\Http\Controllers\Auth\PhoneValidatorController;
use App\Http\Controllers\User\TelemedicineController;
use App\Http\Controllers\User\ActiveAccountController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Chats\BussinesChatController;
use App\Http\Controllers\Chats\PersonalChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\User\ComunicationController;
use App\Http\Controllers\User\InmateController;
use App\Http\Controllers\User\LendingController;
use App\Http\Controllers\User\OpenContractController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\BoletoController;
use App\Http\Controllers\User\PixController;
use App\Http\Controllers\User\InvoiceController;
use App\Http\Controllers\User\KeyController;
use App\Http\Controllers\User\UserCardController;
use App\Http\Controllers\User\UserContract;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ServiceController;
use App\Http\Controllers\User\DocumentController;
use App\Http\Controllers\User\FacialValidationController;
use App\Http\Controllers\User\UserInvestmentController;
use App\Http\Controllers\User\UserTicketController;
use App\Http\Controllers\User\TransferController;
use App\Http\Controllers\User\PaymentBoletoController;
use App\Http\Controllers\User\PaymentPixController;
use App\Http\Controllers\User\PaymentPixKeyController;
use App\Http\Controllers\User\PixKeyController;
use App\Http\Controllers\Webhooks\MyBankController;
use App\Http\Controllers\Webhooks\Starkbank;
use App\Services\MyBank\TokenService;
use App\Services\Payment\Internal\InternalTransfer;
use Illuminate\Support\Facades\Route;
use App\Services\StarbankService;


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

Route::get('/testar-token', function (TokenService $tokenService) {
    $baseUrl = env('MB_API_URL');
    $clientId = env('MB_CLIENT_ID');
    $clientSecret = env('MB_CLIENT_SECRET');
   $grantType = env('MB_GRANT_TYPE');

    $token = $tokenService->generateToken($baseUrl, $clientId, $clientSecret, $grantType);

    return response()->json($token); // Mostra no navegador
});

Route::get('/', function () {
    return view('welcome');
});

//Validate Phone
// Rota para exibir o formulário de telefone
Route::get('/verification/phone', [PhoneValidatorController::class, 'showPhoneForm'])->name('verification.phone');

// Rota para enviar o código de verificação via WhatsApp
Route::post('/send/phone', [PhoneValidatorController::class, 'sendVerificationCode'])->name('send.phone');

// Rota para exibir o formulário de código
Route::get('/verify/code', [PhoneValidatorController::class, 'showCodeForm'])->name('verify.code');

// Rota para validar o código de verificação
Route::post('/validate/code', [PhoneValidatorController::class, 'validateCode'])->name('validate.code');

//Register Personal Account

Route::get('/become/{agency_id}', [RegisteredUserController::class, 'create']);
//Route::post('/register', [RegisteredUserController::class, 'store']);

//Register Business Account
Route::get('/business/{agency_id}',[BusinessController::class, 'create']);
Route::post('/business/register',[BusinessController::class, 'businessRegister'])->name('business.register');



Route::get('/validacao-facial', function () {
    return view('validacao-facial', ['agency' => 1]);
});
Route::post('/validacao-facial', [FacialValidationController::class, 'validarFacial'])->name('validacao-facial');
Route::post('/webhook/Kojahdjha', [Starkbank::class, 'handle']);

//WebHooks
Route::post('/pix/webhook', [PixController::class, 'webhook'])->name('pix.webhook');

//MyBank
Route::post('/webhook/update-user',[MyBankController::class,' updateUser'])->name('webhook.update.user');

// ChatController - PF

// Exibe a página Blade do chat (GET)
Route::get('/chat/onboarding/{agency_id}', [PersonalChatController::class, 'showChat'])->name('chat.view');

// Iniciar Chat
Route::get('/chat/{agency_id}/start', [PersonalChatController::class, 'startChat'])->name('chat.start');
// Processa mensagens do chat (POST)
Route::post('/chat/onboarding/{agency_id}', [PersonalChatController::class, 'message'])->name('chat.onboarding');

// (Opcional) Histórico
Route::get('/chat/history/{agency_id}', [PersonalChatController::class, 'getHistory'])->name('chat.history');
Route::post('/chat/reset', [PersonalChatController::class, 'resetChat'])->name('chat.reset');

// ChatController - PJ

//Inciar Chat
Route::get('/chat/{agency_id}/play', [BussinesChatController::class, 'playChat'])->name('chat.play');
// Exibe a página Blade do chat (GET)
Route::get('/chat/business/{agency_id}', [BussinesChatController::class, 'showChat'])->name('business.view');

// Processa mensagens do chat (POST)
Route::post('/chat/business/{agency_id}', [BussinesChatController::class, 'message'])->name('chat.business');

// (Opcional) Histórico
Route::get('/chat/stories/{agency_id}', [BussinesChatController::class, 'getStories'])->name('chat.stories');
Route::post('/chat/clear', [BussinesChatController::class, 'clearChat'])->name('chat.clear');


//Site Routes
Route::get('remove', [SiteController::class, 'remove'])->name('site.remove');
Route::post('removeStore', [SiteController::class, 'removeStore']);
Route::get('privacy', [SiteController::class, 'privacy'])->name('site.privacy');
Route::get('ralbank', [SiteController::class, 'ralbank'])->name('site.ralbank');
Route::get('liberty', [SiteController::class, 'libertybank'])->name('site.liberty');
Route::get('scontact', [SiteController::class, 'contact'])->name('site.contact');
Route::post('news', [SiteController::class, 'store'])->name('news.store');
Route::post('formContact', [SiteController::class, 'formContact'])->name('form.store');
Route::get('terms', [SiteController::class, 'Term'])->name('site.terms');
Route::get('update', [SiteController::class, 'updateClient'])->name('site.update');

//Inmate
Route::get('inmate', [InmateController::class, 'index'])->name('site.inmate');
Route::resource('/inmateform', InmateController::class);

//Update User
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/checkout', [ActiveAccountController::class, 'checkout'])->name('checkout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user/checkAccount', [UserController::class, 'checkAccount'])->name('checkAccount');

});

//Update User
Route::get('/user/check', [UserController::class, 'userCheck'])->name('user.check');
Route::get('/view/document/email/{email}', [UserController::class, 'validateEmail'])->name('view.validate.email');
Route::post('/check/document', [UserController::class, 'checkEmail'])->name('check.document');
Route::put('/update/user', [UserController::class, 'updateUser'])->name('update.user');
Route::get('/user/checkAccount', [UserController::class, 'checkAccount'])->name('checkAccount');

Route::middleware(['auth', 'role:user', 'checkAccountActive'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::get('/search/{pix_key}', [UserController::class, 'pixKey']);
    Route::get('/balance', [UserController::class, 'balance'])->name('balance');

    Route::get('/transfer', [TransferController::class, 'index'])->name('transfer');
    Route::get('/transfer/extract', [TransferController::class, 'extract'])->name('transfer.extract');
    Route::get('/transfer/accounts', [TransferController::class, 'accounts'])->name('transfer.accounts');
    Route::get('/transfer/accounts/preview', [TransferController::class, 'preview'])->name('transfer.accounts.preview');
    Route::post('/transfer/accounts/preview', [TransferController::class, 'preview'])->name('transfer.accounts.preview');
    Route::post('/transfer/accounts', [TransferController::class, 'store'])->name('transfer.accounts.store');

    //Receive Pix
    Route::get('/pix/qrcode', [PixController::class, 'qrcode'])->name('pix.qrcode');
    Route::post('/pix/qrcode/create', [PixController::class, 'store'])->name('pix.qrcode.store');
    Route::get('/pix/qrcode/{id}', [PixController::class, 'view'])->name('pix.qrcode.view');
    Route::get('/pix/extract', [PixController::class, 'extract'])->name('pix.extract');
    Route::resource('/pix', PixController::class);
    //Payments pix
    Route::get('/payment/pix', [PaymentPixController::class, 'index'])->name('payment.pix');
    Route::get('/payment/pix/preview', [PaymentPixController::class, 'preview'])->name('payment.pix.preview');
    Route::post('/payment/pix/preview', [PaymentPixController::class, 'preview'])->name('payment.pix.preview');
    Route::post('/payment/pix/create', [PaymentPixController::class, 'store'])->name('payment.pix.store');
    Route::get('/payment/pix/extract', [PaymentPixController::class, 'extract'])->name('payment.pix.extract');
    Route::get('/payment/pix/{id}', [PaymentPixController::class, 'view'])->name('payment.pix.view');

    Route::get('/payment/transfer/pix', [PaymentPixKeyController::class, 'index'])->name('transfer.pix');
    Route::get('/payment/transfer/pix/preview', [PaymentPixKeyController::class, 'preview'])->name('transfer.pix.preview');
    Route::post('/payment/transfer/pix/preview', [PaymentPixKeyController::class, 'preview'])->name('transfer.pix.preview');
    Route::post('/payment/transfer/pix', [PaymentPixKeyController::class, 'store'])->name('transfer.pix.transfer');

    Route::resource('/payment', PaymentController::class);
    //Pix Key
    Route::resource('/key', KeyController::class);

    //Message
    Route::resource('/users/comunication', ComunicationController::class);
    //services
    Route::resource('/user/service', ServiceController::class);
    //Loan
    Route::resource('/user/lending', LendingController::class);
    Route::get('/user/lending/create/{loan_id}', [LendingController::class, 'custom'])->name('lending.create.custom');
    Route::get('/user/promoter', [LendingController::class, 'promoter'])->name('user.promoter');
    Route::post('/user/question', [LendingController::class, 'submitQuestion'])->name('user.question');
    //Manager Profile Editing
    Route::get('/user/profile', [UserController::class, 'userProfile'])->name('user.profile');
    Route::post('/user/profile/store', [UserController::class, 'userProfileStore'])->name('user.profile.store');
    //Manager Update Password
    Route::get('/user/change/password', [UserController::class, 'userChangePassword'])->name('user.change.password');
    Route::post('/user/update/password', [UserController::class, 'userUpdatePassword'])->name('user.update.password');
    //Document
    Route::resource('/user/document', DocumentController::class);
    //Card
    Route::resource('/usercard', UserCardController::class);
    //Ticket
    Route::resource('/userticket', UserTicketController::class);
    //Contracts
    Route::resource('/usercontract', UserContract::class);
    //Open Contract
    Route::resource('/opencontract', OpenContractController::class);
    //Invoice
    Route::resource('/invoice', InvoiceController::class);
    //Token
    //PIK essa verificação antes da transação rodou?
    Route::get('/token',[TokenController::class,'showToken'])->name('token.page');
    Route::post('/token/validate',[TokenController::class,'validateToken'])->name('token.validate');

    //
    Route::resource('/managerkey',PixKeyController::class);
    Route::resource('/userbalance',BalanceController::class);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/agency.php';

Route::get('/events', function () {
    $starkbankService = new StarbankService();
    $eventos = $starkbankService->events();
    foreach ($eventos as $evento) {
       // if ($evento->subscription == "transfer") {
            echo '<pre/>';
            print_r($evento);
       // }
    }
});


Route::get('/balance', function () {
    $starkbankService = new StarbankService();
    $balance = $starkbankService->balance();
    dd($balance);
});


Route::get('/reset', function () {
    $exitCode = Artisan::call('clear-compiled');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
});
//Route::get('/webhook/create', [Starkbank::class, 'create']);
//Pix Controller
