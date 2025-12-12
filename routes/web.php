<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ToolCheckoutController;
use App\Http\Controllers\InventoryRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EmployeeMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password-reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password-reset', [PasswordResetController::class, 'resetPassword']);

// Employee Routes (Protected)
Route::middleware([EmployeeMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.form');
    Route::post('/change-password', [PasswordController::class, 'updatePassword'])->name('password.update');

    // Employee Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Tool Checkout
    Route::get('/tools', [ToolCheckoutController::class, 'index'])->name('tools.index');
    Route::get('/tools/{toolCheckout}', [ToolCheckoutController::class, 'show'])->name('tools.show');

    // Inventory Requests
    Route::get('/inventory', [InventoryRequestController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryRequestController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryRequestController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{inventoryRequest}', [InventoryRequestController::class, 'show'])->name('inventory.show');
});

// Admin Routes (Protected)
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('users.resetPassword.form');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::post('/users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
    Route::post('/users/{user}/enable', [UserController::class, 'enable'])->name('users.enable');
});
