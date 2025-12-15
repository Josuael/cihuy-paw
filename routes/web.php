<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanDetailController;


// Member Controllers
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Wallet\MemberWalletController;


// Staff Controllers
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\LoanVerificationController;
use App\Http\Controllers\Staff\PaymentController;
use App\Http\Controllers\Wallet\TopupApprovalController;


// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LoanAuthorizationController;
use App\Http\Controllers\Admin\UserManagementController;


// Ketua Controllers
use App\Http\Controllers\Ketua\KetuaDashboardController;
use App\Http\Controllers\Ketua\LoanApprovalController;

// Shared
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Pdf\BuktiPeminjamanController;
use App\Http\Controllers\Pdf\BuktiAngsuranController;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->middleware('auth');

// =============================
// AUTH CHECK (Laravel Breeze / UI / built-in authentication)
// =============================
Route::middleware(['auth'])->group(function () {

    // =========================
    // MEMBER AREA
    // =========================
    Route::middleware(['role:member'])->group(function () {

        Route::get('/member/dashboard', [MemberDashboardController::class, 'index']);

        // Loan Applications
        Route::resource('/loan-applications', LoanApplicationController::class);

        Route::get('/loan-applications/{application}/documents', [DocumentController::class, 'create'])
        ->name('loan-applications.documents');

        // Upload supporting documents
        Route::post('/loan-applications/{id}/upload', [DocumentController::class, 'store'])
            ->name('loan-applications.upload');

        Route::get('/member/installments', [PaymentController::class, 'memberInstallments'])
            ->name('member.installments');

        Route::post('/member/pay/{schedule_id}', [PaymentController::class, 'memberPay'])
            ->name('member.pay');

        Route::get('/wallet', [MemberWalletController::class, 'index'])
            ->name('wallet.index');

        Route::post('/wallet/topup', [MemberWalletController::class, 'requestTopup'])
            ->name('wallet.topup.request');

        Route::get('/loan/{loan_id}', [LoanDetailController::class, 'show'])
            ->name('loan.show');
    });

    // =========================
    // STAFF KREDIT AREA
    // =========================
    Route::middleware(['role:staff'])->group(function () {

        Route::get('/staff/dashboard', [StaffDashboardController::class, 'index']);

        // List of applications awaiting verification
        Route::get('/staff/loan-applications', [LoanVerificationController::class, 'index']);

        // Verify application
        Route::post('/staff/loan-applications/{id}/verify', [LoanVerificationController::class, 'verify'])
            ->name('staff.verify');

        // Create loan schedule after approval
        Route::post('/staff/schedule/generate/{loan_id}', [ScheduleController::class, 'generate'])
            ->name('schedule.generate');

        Route::post('/staff/pay/{schedule_id}', [PaymentController::class, 'staffPay'])
            ->name('staff.pay');

        
        Route::get('/staff/topup', [TopupApprovalController::class, 'index'])->name('staff.topup.index');
        Route::post('/staff/topup/{id}/approve', [TopupApprovalController::class, 'approve'])->name('staff.topup.approve');
        Route::post('/staff/topup/{id}/reject', [TopupApprovalController::class, 'reject'])->name('staff.topup.reject');

        Route::get('/staff/loan/{loan_id}/installments', [PaymentController::class, 'staffInstallments'])
            ->name('staff.installments');

        Route::post('/staff/pay/{schedule_id}', [PaymentController::class, 'staffPay'])
            ->name('staff.pay');

    });

    // =========================
    // ADMIN AREA
    // =========================
    Route::middleware(['role:admin'])->group(function () {

        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

        // Applications awaiting admin authorization
        Route::get('/admin/authorization', [LoanAuthorizationController::class, 'index'])
            ->name('admin.authorization.index');

        // Admin authorization action
        Route::post('/admin/authorization/{id}/authorize', [LoanAuthorizationController::class, 'authorizeLoan'])
            ->name('admin.authorization.action');
        
        
        Route::resource('/admin/users', UserManagementController::class)
            ->names('admin.users');

    });

    // =========================
    // KETUA AREA
    // =========================
    Route::middleware(['role:ketua'])->group(function () {

        Route::get('/ketua/dashboard', [KetuaDashboardController::class, 'index']);

        // Applications awaiting final approval
        Route::get('/ketua/approval', [LoanApprovalController::class, 'index'])
            ->name('ketua.approval.index');

        // Final Approve
        Route::post('/ketua/approval/{id}/approve', [LoanApprovalController::class, 'approve'])
            ->name('ketua.approve');

        // Reject
        Route::post('/ketua/approval/{id}/reject', [LoanApprovalController::class, 'reject'])
            ->name('ketua.reject');
    }); 

    // Loan PDF (BP)
    Route::get('/pdf/bp/{loan}', 
        [BuktiPeminjamanController::class, 'generate'])
        ->name('pdf.bp')
        ->middleware('can:view,loan');

    // Payment PDF (BA)
    Route::get('/pdf/ba/{payment}', 
        [BuktiAngsuranController::class, 'generate'])
        ->name('pdf.ba')
        ->middleware('can:view,payment');
}); // END auth group
