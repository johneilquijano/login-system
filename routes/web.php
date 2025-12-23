<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\ToolCheckoutController;
use App\Http\Controllers\InventoryRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\OrganizationController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
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
Route::middleware(['auth', 'employee', 'organization'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.form');
    Route::post('/change-password', [PasswordController::class, 'updatePassword'])->name('password.update');

    // Employee Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/sign', [DocumentController::class, 'sign'])->name('documents.sign');
    Route::post('/documents/{document}/sign', [DocumentController::class, 'storeSigning'])->name('documents.storeSign');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Tool Checkout
    Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
    Route::post('/tools/{tool}/checkout', [ToolController::class, 'checkout'])->name('tools.checkout');
    Route::post('/tools/checkouts/{checkout}/return', [ToolController::class, 'return'])->name('tools.return');

    // Inventory Requests
    Route::get('/inventory', [InventoryRequestController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryRequestController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryRequestController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{inventoryRequest}', [InventoryRequestController::class, 'show'])->name('inventory.show');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin', 'organization'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('users.resetPassword.form');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::post('/users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
    Route::post('/users/{user}/enable', [UserController::class, 'enable'])->name('users.enable');

    // Document Management
    Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [AdminDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [AdminDocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [AdminDocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');

    // Tools Inventory
    Route::get('/tools/history', [AdminToolController::class, 'history'])->name('tools.history');
    Route::post('/tools/{tool}/toggle-maintenance', [AdminToolController::class, 'toggleMaintenance'])->name('tools.toggleMaintenance');
    Route::post('/tools/{tool}/force-return', [AdminToolController::class, 'forceReturn'])->name('tools.forceReturn');
    Route::get('/tools', [AdminToolController::class, 'index'])->name('tools.index');
    Route::post('/tools', [AdminToolController::class, 'store'])->name('tools.store');
    Route::get('/tools/{tool}', [AdminToolController::class, 'show'])->name('tools.show');
    Route::put('/tools/{tool}', [AdminToolController::class, 'update'])->name('tools.update');
    Route::delete('/tools/{tool}', [AdminToolController::class, 'destroy'])->name('tools.destroy');
});

// Super Admin Routes (Protected)
Route::middleware(['auth', 'super-admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    // Super Admin Dashboard
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Organization Management
    Route::resource('organizations', OrganizationController::class);
    Route::post('/organizations/{organization}/disable', [OrganizationController::class, 'disable'])->name('organizations.disable');
    Route::post('/organizations/{organization}/enable', [OrganizationController::class, 'enable'])->name('organizations.enable');

    // User Management
    Route::resource('users', SuperAdminUserController::class);
    Route::get('/users/{user}/reset-password', [SuperAdminUserController::class, 'showResetPasswordForm'])->name('users.resetPassword.form');
    Route::post('/users/{user}/reset-password', [SuperAdminUserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::post('/users/{user}/disable', [SuperAdminUserController::class, 'disable'])->name('users.disable');
    Route::post('/users/{user}/enable', [SuperAdminUserController::class, 'enable'])->name('users.enable');
});
