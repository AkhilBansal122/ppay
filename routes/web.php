<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SingleUploadController;
use App\Http\Controllers\Admin\BulkUploadController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\ProductController;
use App\Models\MainCategory;

use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\PayInController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\LedgerController;
use App\Http\Controllers\Admin\WalletTopupController;
use App\Http\Controllers\Admin\WalletTopupRequestController;
use App\Http\Controllers\Admin\ServiceChargeController;

use App\Http\Controllers\CommonController;



Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');
});

Route::middleware('auth')->prefix('admin')->group(function () {
Route::get('/api', function () {
    return view('admin.apiDoc.index'); // resources/views/apiDoc.blade.php
})->name('apiDoc');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('permissions', PermissionController::class);
    Route::post('permissions/data', [PermissionController::class, 'getData'])->name('permissions.data');

    Route::resource('roles', RoleController::class);
    Route::post('roles/data', [RoleController::class, 'getData'])->name('roles.data');
    //Customer
    Route::resource('users', CustomerController::class);
    Route::post('users/data', [CustomerController::class, 'getData'])->name('users.data');
    Route::post('users/statusChange', [CustomerController::class, 'statusChange'])->name('users.statusChange');

    //singleupload
    Route::resource('singleupload', SingleUploadController::class);
    Route::post('singleupload/data', [SingleUploadController::class, 'getData'])->name('singleupload.data');
    Route::post('singleupload/statusChange', [SingleUploadController::class, 'statusChange'])->name('singleupload.statusChange');

    //bulkUpload
    Route::resource('bulkUpload', BulkUploadController::class);
    Route::post('bulkUpload/data', [BulkUploadController::class, 'getData'])->name('bulkUpload.data');
    Route::post('bulkUpload/statusChange', [BulkUploadController::class, 'statusChange'])->name('bulkUpload.statusChange');
    Route::post('dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    Route::post('dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

    Route::resource('payins', PayInController::class);
    Route::post('payins/data', [PayInController::class, 'getData'])->name('payins.data');
    Route::post('payins/export', [PayInController::class, 'export'])->name('payins.export');

    Route::resource('payouts', PayoutController::class);
    Route::post('payouts/data', [PayoutController::class, 'getData'])->name('payouts.data');
    Route::post('payouts/export', [PayoutController::class, 'export'])->name('payouts.export');

    Route::resource('ledgers', LedgerController::class);
    Route::post('ledgers/data', [LedgerController::class, 'getData'])->name('ledgers.data');
        Route::post('ledgers/export', [LedgerController::class, 'export'])->name('ledgers.export');

        Route::resource('wallet-topup', WalletTopupController::class);

        Route::resource('wallet-topup-request', WalletTopupRequestController::class);
            Route::post('wallet-topup-request/data', [WalletTopupRequestController::class, 'getData'])->name('wallet-topup-request.data');

            Route::post('wallet-topup-request/export', [WalletTopupRequestController::class, 'export'])->name('wallet-topup-request.export');
            Route::post('wallet-topup-request/updateWalletRequestStatus', [WalletTopupRequestController::class, 'updateWalletRequestStatus'])->name('wallet-topup-request.updateWalletRequestStatus');


            Route::get('service-charge', [ServiceChargeController::class, 'index'])->name('service-charge');
            Route::post('service-charge.store', [ServiceChargeController::class, 'addServices'])->name('service-charge.store');
            Route::post('service-charge/data', [ServiceChargeController::class, 'getData'])->name('service-charge.data');



            Route::get('commission', [CommonController::class, 'index'])->name('commission.index');
    Route::post('commission/data', [CommonController::class, 'getData'])->name('commission.data');

});

require __DIR__.'/auth.php';
