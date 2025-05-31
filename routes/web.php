<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\App\DashboardController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'version' => env('APP_VERSION', '1.0.1'),
        'appName' => env('APP_NAME', 'AVALON'),
    ]);
});

Route::post('/set-locale/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'bg'])) {
        abort(400);
    }

    session(['locale' => $locale]);
    App::setLocale($locale);

    return response()->noContent();
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('/permissions', PermissionController::class);
});

Route::middleware(['auth', 'exclude.admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';
