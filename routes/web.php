<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Events;
use App\Models\Member;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

// ADMIN
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

// OPERATOR
Route::middleware(['auth', 'role:operator,admin'])->group(function () {
    // Division
    Route::get('division/', [DivisionController::class, 'index'])->name('division.index');
    Route::post('division/store', [DivisionController::class, 'store'])->name('division.store');
    Route::patch('division/update/{division}', [DivisionController::class, 'update'])->name('division.update');
    Route::delete('division/delete/{division}', [DivisionController::class, 'destroy'])->name('division.delete');

    // Member
    Route::get('member/', [MemberController::class, 'index'])->name('member.index');
    Route::post('member/store', [MemberController::class, 'store'])->name('member.store');
    Route::patch('member/update/{member}', [MemberController::class, 'update'])->name('member.update');
    Route::delete('member/delete/{member}', [MemberController::class, 'destroy'])->name('member.delete');
});

/* -------------------------------------------------------------------------
 * KELOLA KONTEN ADMIN: BERITA & KEGIATAN
 * ---------------------------------------------------------------------- */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/news', function () {
        return view('admin.news.index');
    })->name('admin.news.index');

    Route::post('admin/news/store', function (Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug',
            'content' => 'required|string',
            'thumbnail' => 'nullable|string|max:2048',
            'published_at' => 'required|date',
            'status' => 'required|in:draft,published,archived',
        ]);

        $data['user_id'] = Auth::user()->id;
        News::create($data);

        return redirect()->back()->with('success', 'Berita berhasil dipublikasikan.');
    })->name('admin.news.store');

    Route::patch('admin/news/update/{id}', function (Request $request, $id) {
        $news = News::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug,'.$news->id,
            'content' => 'required|string',
            'thumbnail' => 'required|string|max:2048',
            'published_at' => 'required|date',
            'status' => 'required|in:draft,published,archived',
        ]);

        $news->update($data);

        return redirect()->back()->with('success', 'Berita berhasil diperbarui.');
    })->name('admin.news.update');

    Route::delete('admin/news/delete/{id}', function ($id) {
        News::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Berita berhasil dihapus.');
    })->name('admin.news.delete');

    Route::get('admin/events', function () {
        return view('admin.events.index');
    })->name('admin.events.index');

    Route::post('admin/events/store', function (Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug',
            'description' => 'required|string',
            'image' => 'required|string|max:2048',
            'location' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'status' => 'required|in:draft,published,completed,cancelled',
        ]);

        Events::create($data);

        return redirect()->back()->with('success', 'Kegiatan berhasil disimpan.');
    })->name('admin.events.store');

    Route::patch('admin/events/update/{id}', function (Request $request, $id) {
        $event = Events::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,'.$event->id,
            'description' => 'required|string',
            'image' => 'required|string|max:2048',
            'location' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'status' => 'required|in:draft,published,completed,cancelled',
        ]);

        $event->update($data);

        return redirect()->back()->with('success', 'Kegiatan berhasil diperbarui.');
    })->name('admin.events.update');

    Route::delete('admin/events/delete/{id}', function ($id) {
        Events::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    })->name('admin.events.delete');
});
