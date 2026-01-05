<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api', [ApiController::class, 'index'])->name('api.index');
Route::get('/error', [ErrorController::class, 'index'])->name('error');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/components', [ComponentsController::class, 'store'])
        ->name('components.store');
    Route::get('/components/{component}/payload', [ComponentsController::class, 'payload'])
        ->whereNumber('component')
        ->name('components.payload');
    Route::delete('/components/{component}', [ComponentsController::class, 'destroy'])
        ->whereNumber('component')
        ->name('components.destroy');
    Route::get('/components/{category?}', [ComponentsController::class, 'index'])
        ->name('components.index');
});
