<?php

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

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
