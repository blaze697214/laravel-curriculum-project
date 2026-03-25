<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\cdc\CDCDashBoardController;
use App\Http\Controllers\cdc\CDCDepartmentController;
use App\Http\Controllers\cdc\CDCUserController;
use App\Http\Controllers\cdc_dept\CDCDEPTDashBoardController;
use App\Http\Controllers\expert\EXPERTDashBoardController;
use App\Http\Controllers\hod\HODDashBoardController;
use App\Http\Controllers\hod\HODUserController;
use App\Http\Controllers\moderator\MODERATORDashBoardController;
use Illuminate\Support\Facades\Route;

// To add role to middlware function

Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:cdc'])->prefix('/cdc')->name('cdc.')->group(function () {

    Route::get('/dashboard', [CDCDashBoardController::class, 'dashboard']);

    Route::get('/departments', [CDCDepartmentController::class, 'index'])->name('departments.index');

    Route::post('/departments', [CDCDepartmentController::class, 'store'])->name('departments.store');

    Route::put('/departments/{id}', [CDCDepartmentController::class, 'update'])->name('departments.update');

    Route::delete('/departments/{id}', [CDCDepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('/users', [CDCUserController::class, 'index'])->name('users.index');

    Route::post('/users', [CDCUserController::class, 'store'])->name('users.store');

    Route::put('/users/{id}', [CDCUserController::class, 'update'])->name('users.update');

    Route::delete('/users/{id}', [CDCUserController::class, 'destroy'])->name('users.destroy');

});

Route::middleware(['auth', 'role:cdc-dept'])->prefix('/cdc-dept')->name('cdc-dept.')->group(function () {

    Route::get('/dashboard', [CDCDEPTDashBoardController::class, 'dashboard']);

});

Route::middleware(['auth', 'role:hod'])->prefix('/hod')->name('hod.')->group(function () {

    Route::get('/dashboard', [HODDashBoardController::class, 'dashboard']);

    Route::get('/users/moderator', [HODUserController::class, 'moderatorIndex'])->name('users.moderator.index');

    Route::post('/users/moderator', [HODUserController::class, 'store'])->name('users.moderator.store');

    Route::put('/users/moderator/{id}', [HODUserController::class, 'update'])->name('users.moderator.update');

    Route::delete('/users/moderator/{id}', [HODUserController::class, 'destroy'])->name('users.moderator.destroy');

    Route::get('/users/expert', [HODUserController::class, 'expertIndex'])->name('users.expert.index');

    Route::post('/users/expert', [HODUserController::class, 'store'])->name('users.expert.store');

    Route::put('/users/expert/{id}', [HODUserController::class, 'update'])->name('users.expert.update');

    Route::delete('/users/expert/{id}', [HODUserController::class, 'destroy'])->name('users.expert.destroy');

});

Route::middleware(['auth', 'role:moderator'])->prefix('/moderator')->name('moderator.')->group(function () {

    Route::get('/dashboard', [MODERATORDashBoardController::class, 'dashboard']);

});

Route::middleware(['auth', 'role:expert'])->prefix('/expert')->name('expert.')->group(function () {

    Route::get('/dashboard', [EXPERTDashBoardController::class, 'dashboard']);

});

Route::fallback(function () {
    return 122;
});
