<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/', function () {
    return view('landing.index');
});

// Informasi publik: Berita & Kegiatan (view-only, tanpa mengubah backend CRUD)
Route::get('/news', function () {
    return view('news.index');
})->name('berita.index');

Route::get('/news/{slug}', function ($slug) {
    return view('news.show', compact('slug'));
})->name('berita.show');

Route::get('/events', function () {
    return view('events.index');
})->name('kegiatan.index');

Route::get('/events/{slug}', function ($slug) {
    return view('events.show', compact('slug'));
})->name('kegiatan.show');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // User
    Route::get('user/', [UserController::class, 'index'])->name('user.index');
    Route::post('user/store/', [UserController::class, 'store'])->name('user.store');
    Route::patch('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

    // Role
    Route::get('role/', [RoleController::class, 'index'])->name('role.index');
    Route::post('role/store/', [RoleController::class, 'store'])->name('role.store');
    Route::patch('role/update/{role}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('role/delete/{role}', [RoleController::class, 'destroy'])->name('role.delete');
});

Route::middleware(['auth', 'role:operator,admin'])->group(function () {
    // Division
    Route::get('division/', [DivisionController::class, 'index'])->name('division.index');
    Route::post('division/store', [DivisionController::class, 'store'])->name('division.store');
    Route::patch('division/update{id}', [DivisionController::class, 'update'])->name('division.update');
    Route::delete('division/delete{id}', [DivisionController::class, 'destroy'])->name('division.delete');

    // Member
    Route::get('member/', [MemberController::class, 'index'])->name('member.index');
    Route::patch('member/update{id}', [MemberController::class, 'update'])->name('member.update');
    Route::delete('member/delete{id}', [MemberController::class, 'destroy'])->name('member.delete');
});
