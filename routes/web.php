<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers - General
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\Erp\SedeController;
use App\Http\Controllers\Erp\LocalController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'));

Auth::routes();

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Home Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    /*
    |--------------------------------------------------------------------------
    | 🧠 SuperAdmin Routes
    |--------------------------------------------------------------------------
    */
    require __DIR__.'/superadmin.php';
    
    /*
    |--------------------------------------------------------------------------
    | 🏢 Grupo Empresarial Routes
    |--------------------------------------------------------------------------
    */
    require __DIR__.'/workspace.php';
    
    /*
    |--------------------------------------------------------------------------
    | 👥 Gestión General (fuera de grupos)
    |--------------------------------------------------------------------------
    */
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    
    // Vistas generales de Sedes y Locales (solo lectura)
    Route::resource('sedes', SedeController::class)->only(['index', 'show']);
    Route::resource('locales', LocalController::class)->only(['index', 'show']);
    Route::post('locales/{locale}/toggle-status', [LocalController::class, 'toggleStatus'])
        ->name('locales.toggle-status');
    
    /*
    |--------------------------------------------------------------------------
    | 🎨 Personalización del Sistema
    |--------------------------------------------------------------------------
    */
    Route::prefix('customization')->name('customization.')->group(function () {
        Route::get('/', [CustomizationController::class, 'index'])->name('index');
        Route::post('/update', [CustomizationController::class, 'update'])->name('update');
        Route::post('/reset', [CustomizationController::class, 'reset'])->name('reset');
        Route::get('/settings', [CustomizationController::class, 'getSettings'])->name('settings');
    });
});