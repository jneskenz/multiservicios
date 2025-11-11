<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GrupoEmpresaController;
use App\Http\Controllers\Admin\LogController;

/*
|--------------------------------------------------------------------------
| 🧠 SuperAdmin Routes - /admin
|--------------------------------------------------------------------------
| Middleware: auth, superadmin
| Acceso: Solo superadministradores del sistema
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
   ->name('admin.')
   ->middleware(['superadmin'])
   ->group(function () {

      // Dashboard
      Route::get('/', [AdminDashboardController::class, 'index'])->name('index');

      /*
        |--------------------------------------------------------------------------
        | 🏢 Gestión de Grupos Empresariales
        |--------------------------------------------------------------------------
        */
      Route::resource('grupo-empresas', GrupoEmpresaController::class)->names([
         'index'   => 'grupo-empresas.index',
         'create'  => 'grupo-empresas.create',
         'store'   => 'grupo-empresas.store',
         'show'    => 'grupo-empresas.show',
         'edit'    => 'grupo-empresas.edit',
         'update'  => 'grupo-empresas.update',
         'destroy' => 'grupo-empresas.destroy',
      ]);

      // Cambiar estado del grupo (activo/inactivo)
      Route::post('grupo-empresas/{grupo}/toggle-status', [GrupoEmpresaController::class, 'toggleStatus'])
         ->name('grupo-empresas.toggle-status');

      /*
        |--------------------------------------------------------------------------
        | 🧾 Gestión de Logs del Sistema
        |--------------------------------------------------------------------------
        */
      Route::prefix('logs')->name('logs.')->group(function () {
         Route::get('/', [LogController::class, 'index'])->name('index');
         Route::get('/dashboard', fn() => view('admin.logs.dashboard'))->name('dashboard');
         Route::get('/stats', [LogController::class, 'stats'])->name('stats');
         Route::get('/{filename}', [LogController::class, 'show'])->name('show');
         Route::get('/{filename}/download', [LogController::class, 'download'])->name('download');
         Route::delete('/{filename}', [LogController::class, 'delete'])->name('delete');
         Route::post('/clean', [LogController::class, 'clean'])->name('clean');
      });

      /*
        |--------------------------------------------------------------------------
        | 📊 Estadísticas y Reportes Globales
        |--------------------------------------------------------------------------
      */
      // Route::get('/estadisticas', [AdminDashboardController::class, 'estadisticas'])->name('estadisticas');
      // Route::get('/reportes', [AdminDashboardController::class, 'reportes'])->name('reportes');

      /*
        |--------------------------------------------------------------------------
        | ⚙️ Configuración Global del Sistema
        |--------------------------------------------------------------------------
      */
      // Route::get('/configuracion', [AdminDashboardController::class, 'configuracion'])->name('configuracion');
      // Route::post('/configuracion', [AdminDashboardController::class, 'guardarConfiguracion'])->name('configuracion.store');

   });
