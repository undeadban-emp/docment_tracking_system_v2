<?php

use Hashids\Hashids;
use App\Models\Service;
use App\Models\UserService;
use Illuminate\Http\Request;
use App\Models\NotifyMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\DownloadFileController;
use App\Http\Controllers\UserDocumentController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\DocumentQRScannedController;
use App\Http\Controllers\UserAccountSettingCotroller;
use App\Http\Controllers\Admin\AccountSettingController;
use App\Http\Controllers\Admin\ServiceProcessController;
use App\Http\Controllers\Admin\ServiceRequirementController;
use App\Http\Controllers\Admin\ListOfTransactionController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;

Route::get('/', function (Request $request) {
    return view('auth.login');
});


// Auth::routes(['verify' => 'false']);
Auth::routes();

Route::controller(HomeController::class)->group(function () {
    Route::get('home', 'index')->name('home')->middleware('account.approved');
});

Route::group(['middleware' => ['auth', 'account.approved']], function () {
    Route::get('services/list', [ServiceController::class, 'list'])->name('service.list');
    Route::get('services', [ServiceController::class, 'index'])->name('service.index');
    Route::get('service/{service}', [ServiceController::class, 'show'])->name('service.show');
    Route::post('service/apply/{service}', [ServiceController::class, 'apply'])->name('service.apply');
    Route::post('service/reapply/{trackingNumber}/apply', [ServiceController::class, 'reapply'])->name('service.reapply');
    Route::get('service/document/incoming', [ServiceController::class, 'incoming'])->name('service.incoming');
    Route::get('service/document/incoming/datas', [ServiceController::class, 'incomingData'])->name('service.incoming.datas');
    Route::get('service/document/incomingList', [ServiceController::class, 'incomingList']);


    Route::get('service/document/outgoing', [ServiceController::class, 'outgoing'])->name('service.outgoing');
    Route::get('service/document/outgoing/datas', [ServiceController::class, 'outgoingData'])->name('service.outgoing.datas');

    Route::get('service/document/for-release', [ServiceController::class, 'forRelease'])->name('service.for-release');
    Route::get('for-release', [ServiceController::class, 'forReleaseData'])->name('service.forRelease.datas');

    Route::get('service/document/return', [ServiceController::class, 'return'])->name('service.return');
    Route::get('service/document/manage', [ServiceController::class, 'manage'])->name('service.manage');
    Route::get('track-my-document', [ServiceController::class, 'trackMyDocument'])->name('service.track-my-document');

    Route::get('received/service/{trackingNumber}', [ServiceController::class, 'received'])->name('service.received');
    Route::post('service/received/document/{trackingNumber}', [ServiceController::class, 'documentReceived'])->name('service.document.received');
    Route::post('service/forward/{service}', [ServiceController::class, 'forward'])->name('service.forward');

    Route::get('user/documents', [UserDocumentController::class, 'index'])->name('user.documents');
    Route::get('user/documents/list', [UserDocumentController::class, 'listMyDocs'])->name('user.documents.listMyDocs');
    Route::get('user/document/{transactionCode}/{serviceID}', [UserDocumentController::class, 'show'])->name('user.document.show');
    Route::get('document/return/{transactionCode}', [UserDocumentController::class, 'edit'])->name('user.document.edit');

    Route::delete('user/document/delete/{trackingNumber}', [UserDocumentController::class, 'delete'])->name('user.document.cancel');

    Route::get('account', [UserAccountSettingCotroller::class, 'showUserAccountPage'])->name('user.account.settings');
    Route::post('account/update', [UserAccountSettingCotroller::class, 'update'])->name('user.account.settings.update');

    Route::get('print-qr/{serviceID}', function (string $trackingNumber) {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&amp;data=' . route('document-qr-scanned', $trackingNumber) . '" alt=""><br>'.'<p style="margin-top:2px;font-size: 18px;">'.$trackingNumber.'</p>');
        return $pdf->stream();
    })->name('print-document-qr');

    Route::get('/download/file/{fileName}', [DownloadFileController::class, 'download'])->name('download.file');

    Route::get('logs', [UserLogController::class, 'index'])->name('user.logs');
    Route::get('/logs/list/checker', [UserLogController::class, 'listChecker'])->name('logs.list.checker');
    Route::get('/logs/list/liaison', [UserLogController::class, 'listLiaison'])->name('logs.list.liaison');
});

Route::get('user/account/verification', function (Request $request) {
    $request->session()->flush();
    return view('user-account-verification');
})->name('user.account.verification');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [LoginController::class, 'login']);
    Route::get('login', [LoginController::class, 'login'])->name('admin.auth.login');
    Route::post('login', [LoginController::class, 'submit'])->name('admin.submit.auth.login');
    Route::post('logout', [LoginController::class, 'logout'])->name('admin.auth.logout');

    Route::get('dashboard', [AdminHomeController::class, 'index'])->name('admin.dashboard');
    Route::get('dashboard/data', [AdminHomeController::class, 'indexData']);


    Route::name('admin.')->group(function () {
         // logs
        Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
        Route::resource('position', PositionController::class);
        Route::get('position/edit/{code}', [PositionController::class, 'edit']);
        Route::get('position/data/all', [PositionController::class, 'indexPositionData']);
        Route::delete('position/delete/{code}', [PositionController::class, 'destroy']);
        Route::resource('office', OfficeController::class);
        Route::get('office/data/all', [OfficeController::class, 'indexOfficeData']);
        Route::get('office/edit/{code}', [OfficeController::class, 'edit']);
        Route::delete('office/delete/{code}', [OfficeController::class, 'destroy']);
        Route::resource('service', AdminServiceController::class);
        Route::delete('service/delete/{code}', [AdminServiceController::class, 'destroy']);
        Route::resource('service-requirements', ServiceRequirementController::class);
        Route::resource('service-process', ServiceProcessController::class);
        Route::resource('list-of-transaction', ListOfTransactionController::class);
        Route::get('list-of-transaction/list/all/{officeCode}/{status}', [ListOfTransactionController::class, 'listOfTransaction']);
        Route::get('document/{transactionCode}/{serviceID}', [ListOfTransactionController::class, 'show']);
        Route::delete('document/delete/{trackingNumber}', [ListOfTransactionController::class, 'destroy']);

        Route::get('service-process-scan/{serviceID}', function ($serviceID) {
            // Check the user_service table if there's incoming or current stage
            $userService = UserService::where('service_id', $serviceID)->where('stage', 'current')->orWhere('stage', 'incoming')->count();
            return response()->json(['having_on_process' => $userService]);
        })->name('service-process.check-on-process-document');

        Route::get('service-process/change/process/{service_id}/order', [ServiceProcessController::class, 'changeProcessOrder'])->name('change.process.order');
        Route::patch('service-process/change/process/order/{service_id}', [ServiceProcessController::class, 'updateProcessOrder'])->name('update.process.order');


        Route::resource('user', UserController::class);
        Route::get('user/data/all', [UserController::class, 'indexOfficeData']);
        Route::get('user/edit/{id}', [UserController::class, 'edit']);
        Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
        Route::get('user/reset/{id}', [UserController::class, 'reset']);



        Route::patch('user/approve/{user_id}', [UserController::class, 'approve'])->name('user.approve');
        Route::patch('user/reject/{user_id}', [UserController::class, 'reject'])->name('user.reject');

        Route::get('account', [AccountSettingController::class, 'showAccountPage'])->name('account.settings');
        Route::post('account/update', [AccountSettingController::class, 'update'])->name('account.settings.update');
    });
});


Route::get('document-qr-scanned/{trackingNumber}', [DocumentQRScannedController::class, 'index'])->middleware('auth')->name('document-qr-scanned');