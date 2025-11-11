<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Workspace\AppsController;
use App\Http\Controllers\Workspace\EmpresaController;
use App\Http\Controllers\Workspace\GrupoDashboardController;
use App\Http\Controllers\Workspace\LocalController;
use App\Http\Controllers\Workspace\SedeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Workspace\RoleController;


/*
|--------------------------------------------------------------------------
| 🏢 Panel Grupo Empresarial - /{grupo}
|--------------------------------------------------------------------------
| Middleware: auth, grupo-empresa.access, rol-administrador
| Acceso: Superusuario, Propietario, Administrador General del Grupo
|--------------------------------------------------------------------------
*/

Route::prefix('{grupo}')
    ->name('grupo.')
    ->middleware(['grupo-empresa.access', 'rol-administrador'])
    ->group(function () {
        
        /*
        |--------------------------------------------------------------------------
        | 📊 Dashboard y Apps
        |--------------------------------------------------------------------------
        */
        Route::get('/', [GrupoDashboardController::class, 'index'])->name('dashboard');
        Route::get('/apps', [AppsController::class, 'index'])->name('apps');
        
        /*
        |--------------------------------------------------------------------------
        | 🏭 Gestión de Empresas
        |--------------------------------------------------------------------------
        */
        Route::resource('empresas', EmpresaController::class);
        Route::post('empresas/{empresa}/activar', [EmpresaController::class, 'activar'])->name('empresas.activar');
        Route::post('empresas/{empresa}/desactivar', [EmpresaController::class, 'desactivar'])->name('empresas.desactivar');
        
        /*
        |--------------------------------------------------------------------------
        | 🏬 Gestión de Sedes
        |--------------------------------------------------------------------------
        */
        Route::resource('sedes', SedeController::class);
        Route::post('sedes/{sede}/activar', [SedeController::class, 'activar'])->name('sedes.activar');
        Route::post('sedes/{sede}/desactivar', [SedeController::class, 'desactivar'])->name('sedes.desactivar');
        
        /*
        |--------------------------------------------------------------------------
        | 🏪 Gestión de Locales
        |--------------------------------------------------------------------------
        */
        Route::resource('locales', LocalController::class);
        Route::post('locales/{local}/activar', [LocalController::class, 'activar'])->name('locales.activar');
        Route::post('locales/{local}/desactivar', [LocalController::class, 'desactivar'])->name('locales.desactivar');
        Route::post('locales/{local}/toggle-status', [LocalController::class, 'toggleStatus'])->name('locales.toggle-status');
        
        /*
        |--------------------------------------------------------------------------
        | 👥 Gestión de Usuarios del Grupo
        |--------------------------------------------------------------------------
        */
        Route::resource('usuarios', UserController::class);
        Route::post('usuarios/{usuario}/activar', [UserController::class, 'activar'])->name('usuarios.activar');
        Route::post('usuarios/{usuario}/desactivar', [UserController::class, 'desactivar'])->name('usuarios.desactivar');
        Route::post('usuarios/{usuario}/asignar-empresa', [UserController::class, 'asignarEmpresa'])->name('usuarios.asignar-empresa');
        Route::post('usuarios/{usuario}/asignar-sede', [UserController::class, 'asignarSede'])->name('usuarios.asignar-sede');
        Route::post('usuarios/{usuario}/asignar-local', [UserController::class, 'asignarLocal'])->name('usuarios.asignar-local');
        
        /*
        |--------------------------------------------------------------------------
        | 🔐 Roles y Permisos del Grupo
        |--------------------------------------------------------------------------
        */
        Route::resource('roles', RoleController::class);
        Route::post('roles/{rol}/asignar-permisos', [RoleController::class, 'asignarPermisos'])->name('roles.asignar-permisos');


        /*
        |--------------------------------------------------------------------------
        | 🔐 Roles y Permisos del Grupo
        |--------------------------------------------------------------------------
        */
        // Route::prefix('roles')->name('roles.')->group(function () {
        //     Route::get('/', [UsuarioController::class, 'roles'])->name('index');
        //     Route::post('/', [UsuarioController::class, 'crearRol'])->name('store');
        //     Route::put('/{rol}', [UsuarioController::class, 'actualizarRol'])->name('update');
        //     Route::delete('/{rol}', [UsuarioController::class, 'eliminarRol'])->name('destroy');
        // });
        
        // Route::get('permisos', [UsuarioController::class, 'permisos'])->name('permisos.index');
        
        /*
        |--------------------------------------------------------------------------
        | ⚙️ Configuración del Grupo
        |--------------------------------------------------------------------------
        */
        Route::get('configuracion', [GrupoDashboardController::class, 'configuracion'])->name('configuracion');
        Route::post('configuracion', [GrupoDashboardController::class, 'guardarConfiguracion'])->name('configuracion.store');
        
        /*
        |--------------------------------------------------------------------------
        | 💳 Plan y Módulos
        |--------------------------------------------------------------------------
        */
        Route::get('plan', [GrupoDashboardController::class, 'plan'])->name('plan');
        Route::get('modulos', [GrupoDashboardController::class, 'modulos'])->name('modulos');
        Route::post('modulos/{modulo}/activar', [GrupoDashboardController::class, 'activarModulo'])->name('modulos.activar');
        Route::post('modulos/{modulo}/desactivar', [GrupoDashboardController::class, 'desactivarModulo'])->name('modulos.desactivar');
        
        /*
        |--------------------------------------------------------------------------
        | 📈 Reportes del Grupo
        |--------------------------------------------------------------------------
        */
        Route::get('reportes', [GrupoDashboardController::class, 'reportes'])->name('reportes');
        Route::get('reportes/{tipo}', [GrupoDashboardController::class, 'generarReporte'])->name('reportes.generar');
        Route::get('reportes/{tipo}/descargar', [GrupoDashboardController::class, 'descargarReporte'])->name('reportes.descargar');
    });