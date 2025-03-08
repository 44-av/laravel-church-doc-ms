<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParishionerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PriestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

// GOOGLE AUTH ROUTES
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);


// Dashboard Redirect Based on Role
Route::get('/dashboard', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'Admin'
            ? redirect()->route('admin_dashboard')
            : redirect()->route('parishioner_dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware('Admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin_dashboard');
    Route::get('/admin/documents', [DocumentController::class, 'index'])->name('documents');
    Route::get('/admin/priest', [PriestController::class, 'index'])->name('priests');
    Route::get('/admin/mail', [MailController::class, 'index'])->name('mails');
    Route::get('/admin/donations', [DonationController::class, 'index'])->name('donations');
    Route::get('/admin/approval_request', [RequestController::class, 'approval_request'])->name('approval_request');
    Route::get('/admin/payment', [TransactionController::class, 'index'])->name('payment');
    Route::get('/transactions', [TransactionController::class, 'showTable'])->name('transactions.index');
    Route::get('/admin/announcement', [AnnouncementController::class, 'index'])->name('announcement');
    Route::get('/admin/donation/show', [DonationController::class, 'showDonations'])->name('show_donations');
    // Route::get('/admin/donation/show', [DonationController::class, 'showPayment'])->name('show_payment');
    Route::get('/admin/payment', [TransactionController::class, 'index'])->name('payment');


    Route::post('/admin/priest', [PriestController::class, 'store'])->name('priest.store');
    Route::post('/admin/mail', [MailController::class, 'store'])->name('mail.store');
    Route::post('/admin/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/admin/documents/ocr', [DocumentController::class, 'processOCR'])->name('documents.ocr');
    Route::post('/admin/announcement', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::post('/admin/request_baptismal', [AdminController::class, 'requestBaptismal'])->name('baptismal.store');
    Route::post('/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');

    Route::put('/admin/priest/{id}', [PriestController::class, 'update'])->name('priest.update');
    Route::put('/admin/mail/{id}', [MailController::class, 'update'])->name('mail.update');
    Route::put('/admin/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
    Route::put('/admin/approval_request/{id}', [RequestController::class, 'approve_request'])->name('approval_request.update');
    Route::put('/admin/donations/{id}', [DonationController::class, 'update'])->name('donation.update');
    Route::put('/admin/donations/{id}/status', [DonationController::class, 'updateStatus'])->name('donation.updateStatus');
    Route::put('/admin/announcement/{id}', [AnnouncementController::class, 'update'])->name('announcement.update');

    Route::delete('/admin/priest/{id}', [PriestController::class, 'destroy'])->name('priest.destroy');
    Route::delete('/admin/mail/{id}', [MailController::class, 'destroy'])->name('mail.destroy');
    Route::delete('/admin/donations/{id}', [DonationController::class, 'destroy'])->name('donation.destroy');
    Route::delete('/admin/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::delete('/admin/announcement/{id}', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');
    Route::delete('/admin/approval_request/{id}', [RequestController::class, 'destroy'])->name('approval_request.destroy');

    Route::post('/admin/documents/{id}', [DocumentController::class, 'restore'])->name('documents.restore');

});

// Parishioner Routes
Route::middleware('Parishioner')->group(function () {
    Route::get('/parishioner/dashboard', [ParishionerController::class, 'index'])->name('parishioner_dashboard');
    Route::get('/parishioner/request', [RequestController::class, 'index'])->name('request');
    Route::get('/parishioner/donations', [DonationController::class, 'parishionerIndex'])->name('parishioner_donation');

    Route::post('/parishioner/request', [RequestController::class, 'store'])->name('request.store');
    Route::post('/parishioner/donations', [DonationController::class, 'store'])->name('donation.store');

    Route::put('/parishioner/request/{id}', [RequestController::class, 'update'])->name('request.update');
    Route::put('/payment/update/{id}/{amount_paid}/{transaction_id}', [PaymentController::class, 'update'])->name('payment.update');

    Route::put('/parishioner/payment/{id}', [RequestController::class, 'updatePayment'])->name('payment.update');

    Route::delete('/parishioner/request/{id}', [RequestController::class, 'destroy'])->name('request.destroy');
});

// Authentication Routes
require __DIR__ . '/auth.php';
