<?php

use App\Http\Controllers\DivisionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// ADMIN
// Route::group('Admin', function(){});

// User
Route::get('user/', [UserController::class, 'index'])->name('user.index');
Route::patch('user/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

// Role
Route::get('role/', [RoleController::class, 'index'])->name('division.index');
Route::patch('role/update{role}', [RoleController::class, 'update'])->name('division.update');
Route::delete('role/delete/{role}', [RoleController::class, 'destroy'])->name('division.delete');

// Division
Route::get('division/', [DivisionController::class, 'index'])->name('division.index');
Route::patch('division/update{division}', [DivisionController::class, 'update'])->name('division.update');
Route::delete('division/delete{division}', [DivisionController::class, 'destroy'])->name('division.delete');

// Member
Route::get('member/', [MemberController::class, 'index'])->name('member.index');
Route::patch('member/update{member}', [MemberController::class, 'update'])->name('member.update');
Route::delete('member/delete{member}', [MemberController::class, 'destroy'])->name('member.delete');
