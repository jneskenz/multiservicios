<?php

use App\Http\Controllers\Admin\GrupoEmpresaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Rutas de administración de logs (solo para superadmin)
Route::prefix('admin')->name('admin.')->middleware('superadmin')->group(function () {

   // Ruta de admin dashboard
   Route::get('/', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('index');

   // Rutas de Grupos Empresariales
   Route::resource('grupo-empresas', GrupoEmpresaController::class)->names([
      'index' => 'grupo-empresas.index',
      'create' => 'grupo-empresas.create',
      'store' => 'grupo-empresas.store',
      'show' => 'grupo-empresas.show',
      'edit' => 'grupo-empresas.edit',
      'update' => 'grupo-empresas.update',
      'destroy' => 'grupo-empresas.destroy',
   ]);
   Route::post('grupo-empresas/{grupo}/toggle-status', [GrupoEmpresaController::class, 'toggleStatus'])->name('grupo-empresas.toggle-status');


   // Rutas de Logs
   Route::get('logs', [App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs.index');
   Route::get('logs/dashboard', function () {
      return view('admin.logs.dashboard');
   })->name('logs.dashboard');
   Route::get('logs/stats', [App\Http\Controllers\Admin\LogController::class, 'stats'])->name('logs.stats');
   Route::get('logs/{filename}', [App\Http\Controllers\Admin\LogController::class, 'show'])->name('logs.show');
   Route::get('logs/{filename}/download', [App\Http\Controllers\Admin\LogController::class, 'download'])->name('logs.download');
   Route::delete('logs/{filename}', [App\Http\Controllers\Admin\LogController::class, 'delete'])->name('logs.delete');
   Route::post('logs/clean', [App\Http\Controllers\Admin\LogController::class, 'clean'])->name('logs.clean');


   
});
