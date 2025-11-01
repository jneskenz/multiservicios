<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Importar controladores
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\Erp\SedeController;
use App\Http\Controllers\Erp\LocalController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GrupoEmpresaController;
use App\Http\Controllers\Admin\LogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aquí se definen todas las rutas del sistema que requieren autenticación.
| Cada grupo aplica su propio middleware, prefijo y namespace para mantener
| un orden limpio y modular.
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 🧠 Superadministrador
    |--------------------------------------------------------------------------
    | Todas las rutas con prefijo /admin y middleware superadmin
    */
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('superadmin')
        ->group(function () {

        // 🏠 Dashboard del SuperAdmin
        Route::get('/', [AdminDashboardController::class, 'index'])->name('index');

        // 🏢 CRUD de Grupos Empresariales
        Route::resource('grupo-empresas', GrupoEmpresaController::class)->names([
            'index'   => 'grupo-empresas.index',
            'create'  => 'grupo-empresas.create',
            'store'   => 'grupo-empresas.store',
            'show'    => 'grupo-empresas.show',
            'edit'    => 'grupo-empresas.edit',
            'update'  => 'grupo-empresas.update',
            'destroy' => 'grupo-empresas.destroy',
        ]);

        // 🔄 Cambiar estado (activo/inactivo)
        Route::post('grupo-empresas/{grupo}/toggle-status', [GrupoEmpresaController::class, 'toggleStatus'])
            ->name('grupo-empresas.toggle-status');

        // 🧾 Gestión de Logs del Sistema
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [LogController::class, 'index'])->name('index');
            Route::get('/dashboard', fn () => view('admin.logs.dashboard'))->name('dashboard');
            Route::get('/stats', [LogController::class, 'stats'])->name('stats');
            Route::get('/{filename}', [LogController::class, 'show'])->name('show');
            Route::get('/{filename}/download', [LogController::class, 'download'])->name('download');
            Route::delete('/{filename}', [LogController::class, 'delete'])->name('delete');
            Route::post('/clean', [LogController::class, 'clean'])->name('clean');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 🏠 Rutas generales del sistema (usuarios autenticados)
    |--------------------------------------------------------------------------
    */

    Log::info('Ingresa a home', ['url' => request()->path()]);


    // Página principal después de login
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // 👤 Usuarios
    Route::resource('users', UserController::class);

    // 🔑 Roles
    Route::resource('roles', RoleController::class);

    // 🏢 Empresas
    // Route::resource('empresas', EmpresaController::class);

    // 🏬 Sedes
    Route::resource('sedes', SedeController::class);

    // 🏪 Locales
    Route::resource('locales', LocalController::class);
    Route::post('locales/{locale}/toggle-status', [LocalController::class, 'toggleStatus'])->name('locales.toggle-status');

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
