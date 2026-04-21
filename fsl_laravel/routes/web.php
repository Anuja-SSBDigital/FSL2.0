<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('showForgotPasswordForm');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard
Route::middleware('auth.fluree')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/userpage', [UserController::class, 'userpage'])->name('userpage');

    // Case routes
    Route::get('/cases/divisions/{deptId}', [App\Http\Controllers\CaseController::class, 'divisions'])->name('cases.divisions');
    Route::get('/cases/check-number', [App\Http\Controllers\CaseController::class, 'checkNumber'])->name('cases.check-number');
    Route::get('/cases/users-by-department', [App\Http\Controllers\CaseController::class, 'getUsersByDepartment'])->name('cases.users-by-department');
    Route::get('/cases/create', [App\Http\Controllers\CaseController::class, 'create'])->name('cases.create');
    Route::post('/cases/create', [App\Http\Controllers\CaseController::class, 'store'])->name('cases.store');
    Route::get('/cases/add-details/{caseno}', [App\Http\Controllers\CaseController::class, 'addDetails'])->name('cases.add-details')->where('caseno', '.+');
    Route::put('/cases/add-details/{caseno}', [App\Http\Controllers\CaseController::class, 'updateDetails'])->name('cases.update-details')->where('caseno', '.+');
    Route::get('/cases/assign/{caseno}', [App\Http\Controllers\CaseController::class, 'assign'])->name('cases.assign')->where('caseno', '.+');
    Route::post('/cases/assign/{caseno}', [App\Http\Controllers\CaseController::class, 'assignCase'])->name('cases.assign-case')->where('caseno', '.+');

    // Evidence Acceptance routes
    Route::get('/acceptance', [App\Http\Controllers\CaseController::class, 'acceptanceForm'])->name('cases.acceptance-form');
    Route::post('/acceptance', [App\Http\Controllers\CaseController::class, 'acceptanceStore'])->name('cases.acceptance-store');
    Route::get('/acceptance/{evidenceId}/edit', [App\Http\Controllers\CaseController::class, 'acceptanceEdit'])->name('cases.acceptance-edit');
    Route::put('/acceptance/{evidenceId}', [App\Http\Controllers\CaseController::class, 'acceptanceUpdate'])->name('cases.acceptance-update');
});
