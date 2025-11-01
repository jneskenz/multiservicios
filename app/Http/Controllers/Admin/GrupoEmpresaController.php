<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrupoEmpresa;
use App\Models\Pais;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class GrupoEmpresaController extends Controller
{
   /**
    * Listar todos los grupos empresariales
    */
   public function index(Request $request)
   {
      // traer datos de pais
      $query = GrupoEmpresa::with(['propietarios', 'paises']);

      // Filtros
      if ($request->filled('search')) {
         $search = $request->search;
         $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
               ->orWhere('slug', 'like', "%{$search}%")
               ->orWhere('ruc', 'like', "%{$search}%");
         });
      }

      if ($request->filled('plan')) {
         $query->where('plan_actual', $request->plan);
      }

      if ($request->filled('estado')) {
         $query->where('estado', $request->estado);
      }

      $grupo_empresas = $query->latest()->paginate(20);

      return view('admin.grupo-empresas.index', compact('grupo_empresas'));
   }

   /**
    * Mostrar formulario de creación
    */
   public function create()
   {
      $planes = ['gratuito', 'basico', 'profesional', 'enterprise'];
      $paises = Pais::where('estado', true)->get();

      return view('admin.grupo-empresas.create', compact('planes', 'paises'));
   }

   /**
    * Crear nuevo grupo empresarial
    */
   public function store(Request $request)
   {

      try {
         $user = User::where('id', Auth::id())->first();
         if ($user->grupo_empresa_id || $user->isSuperAdmin()) {

            // agregar user_uuid
            // $request->merge(['user_uuid' => Str::uuid()]);
            $request->merge(['user_uuid' => Str::uuid()->toString()]);


            $validated = $request->validate([
               'nombre' => 'required|string|max:255',
               'ruc' => 'nullable|string|max:20|unique:grupo_empresas,ruc',
               'razon_social' => 'nullable|string|max:255',
               'email' => 'required|email|unique:grupo_empresas,email',
               'telefono' => 'nullable|string|max:20',
               'descripcion' => 'nullable|string',
               'direccion' => 'nullable|string',
               'max_empresas' => 'required|integer|min:1',
               'pais_id' => 'required|string',
               'plan_actual' => 'required|string',
               'user_uuid' => 'required|uuid|unique:grupo_empresas,user_uuid',
            ]);

            // Generar slug único
            $validated['slug'] = Str::slug($validated['nombre']);
            $originalSlug = $validated['slug'];
            $count = 1;

            while (GrupoEmpresa::where('slug', $validated['slug'])->exists()) {
               $validated['slug'] = $originalSlug . '-' . $count;
               $count++;
            }

            $validated['activo'] = true;

            $grupo = GrupoEmpresa::create($validated);

            // Registrar en activity log
            activity()
               ->performedOn($grupo)
               ->causedBy(Auth::user())
               ->withProperties($validated)
               ->log('Creó nuevo grupo empresarial');

            return redirect()
               ->route('admin.grupo-empresas.index')
               ->with('success', 'Grupo empresarial creado correctamente.');
         } else {

            // call back with error
            return redirect()
               ->back()
               ->withInput()
               ->with('error', 'No tienes permisos para crear un grupo empresarial.');
         }
      } catch (\Exception $e) {
         return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Error al crear el grupo empresarial: ' . $e->getMessage());
      }
   }

   /**
    * Mostrar detalle del grupo
    */
   public function show(GrupoEmpresa $grupo_empresa)
   {
      $grupo_empresa->load('propietarios', 'empresas', 'usuarios');

      // Estadísticas del grupo
      $stats = [
         'total_empresas' => $grupo_empresa->empresas()->count(),
         'empresas_activas' => $grupo_empresa->empresas()->where('activo', true)->count(),
         'total_usuarios' => $grupo_empresa->usuarios()->count(),
         'usuarios_activos' => $grupo_empresa->usuarios()->where('activo', true)->count(),
      ];

      // Actividad reciente del grupo
      // $actividad = activity()
      //    ->inLog('default')
      //    ->where('subject_type', GrupoEmpresa::class)
      //    ->where('subject_id', $grupo_empresa->id)
      //    ->latest()
      //    ->take(20)
      //    ->get();

      $actividad = Activity::inLog('default')
         ->where('subject_type', GrupoEmpresa::class)
         ->where('subject_id', $grupo_empresa->id)
         ->latest()
         ->take(20)
         ->get();

      return view('admin.grupo-empresas.show', compact('grupo_empresa', 'stats', 'actividad'));
   }

   /**
    * Mostrar formulario de edición
    */
   public function edit(GrupoEmpresa $grupo_empresa)
   {
      $planes = ['gratuito', 'basico', 'profesional', 'enterprise'];
      $paises = Pais::where('estado', true)->get();

      // dd($grupo_empresa->id);

      return view('admin.grupo-empresas.edit', compact('grupo_empresa', 'planes', 'paises'));
   }

   /**
    * Actualizar grupo empresarial
    */
   public function update(Request $request, GrupoEmpresa $grupo_empresa)
   {
      $validated = $request->validate([
         'nombre' => 'required|string|max:255',
         'ruc' => 'nullable|string|max:20|unique:grupo_empresas,ruc,' . $grupo_empresa->id,
         'razon_social' => 'nullable|string|max:255',
         'email' => 'required|email|unique:grupo_empresas,email,' . $grupo_empresa->id,
         'telefono' => 'nullable|string|max:20',
         'pais' => 'required|string',
         'direccion' => 'nullable|string',
         'max_empresas' => 'required|integer|min:1',
         'max_usuarios' => 'required|integer|min:1',
      ]);

      $grupo_empresa->update($validated);

      activity()
         ->performedOn($grupo_empresa)
         ->causedBy(Auth::user())
         ->withProperties([
            'old' => $grupo_empresa->getOriginal(),
            'new' => $validated
         ])
         ->log('Actualizó grupo empresarial');

      return redirect()
         ->route('admin.grupo-empresas.show', $grupo_empresa)
         ->with('success', 'Grupo empresarial actualizado correctamente.');
   }

   /**
    * Eliminar grupo empresarial (soft delete)
    */
   public function destroy(GrupoEmpresa $grupo_empresa)
   {
      // Verificar si tiene empresas activas
      if ($grupo_empresa->empresas()->where('activo', true)->count() > 0) {
         return redirect()
            ->back()
            ->with('error', 'No se puede eliminar un grupo con empresas activas.');
      }

      activity()
         ->performedOn($grupo_empresa)
         ->causedBy(Auth::user())
         ->log('Eliminó grupo empresarial');

      $grupo_empresa->delete();

      return redirect()
         ->route('admin.grupo-empresas.index')
         ->with('success', 'Grupo empresarial eliminado correctamente.');
   }

   /**
    * Activar grupo empresarial
    */
   public function activar(GrupoEmpresa $grupo_empresa)
   {
      $grupo_empresa->update(['activo' => true]);

      activity()
         ->performedOn($grupo_empresa)
         ->causedBy(Auth::user())
         ->log('Activó grupo empresarial');

      return redirect()
         ->back()
         ->with('success', 'Grupo empresarial activado correctamente.');
   }

   /**
    * Suspender grupo empresarial
    */
   public function suspender(GrupoEmpresa $grupo_empresa)
   {
      $grupo_empresa->update(['activo' => false]);

      activity()
         ->performedOn($grupo_empresa)
         ->causedBy(Auth::user())
         ->log('Suspendió grupo empresarial');

      return redirect()
         ->back()
         ->with('success', 'Grupo empresarial suspendido correctamente.');
   }

   /**
    * Cambiar plan del grupo
    */
   public function cambiarPlan(Request $request, GrupoEmpresa $grupo_empresa)
   {
      $validated = $request->validate([
         'plan_actual' => 'required|string|in:gratuito,basico,profesional,enterprise',
         'max_empresas' => 'required|integer|min:1',
         'max_usuarios' => 'required|integer|min:1',
         'fecha_inicio_plan' => 'required|date',
         'fecha_fin_plan' => 'required|date|after:fecha_inicio_plan',
         'modulos_disponibles' => 'nullable|array',
      ]);

      $grupo_empresa->update($validated);

      activity()
         ->performedOn($grupo_empresa)
         ->causedBy(Auth::user())
         ->withProperties([
            'plan_anterior' => $grupo_empresa->getOriginal('plan_actual'),
            'plan_nuevo' => $validated['plan_actual']
         ])
         ->log('Cambió plan del grupo empresarial');

      return redirect()
         ->back()
         ->with('success', 'Plan actualizado correctamente.');
   }

   /**
    * Cambiar estado del grupo empresarial (activo/inactivo)
    */
   public function toggleStatus(GrupoEmpresa $grupo)
   {
      try {
         // Cambiar el estado
         $nuevoEstado = !$grupo->estado;
         $grupo->update(['estado' => $nuevoEstado]);

         // Registrar actividad
         // activity()
         //    ->performedOn($grupo)
         //    ->causedBy(Auth::user())
         //    ->withProperties([
         //       'estado_anterior' => !$nuevoEstado,
         //       'estado_nuevo' => $nuevoEstado
         //    ])
         //    ->log($nuevoEstado ? 'Activó grupo empresarial' : 'Desactivó grupo empresarial');
         
         Activity::create([
            'log_name' => 'default',
            'description' => $nuevoEstado ? 'Activó grupo empresarial' : 'Desactivó grupo empresarial',
            'subject_type' => GrupoEmpresa::class,
            'subject_id' => $grupo->id,
            'causer_type' => User::class,
            'causer_id' => Auth::id(),
            'properties' => [
               'estado_anterior' => !$nuevoEstado,
               'estado_nuevo' => $nuevoEstado
            ],
         ]);

         return redirect()
            ->back()
            ->with('success', 'Estado del grupo empresarial actualizado correctamente.');
      } catch (\Exception $e) {
         return redirect()
            ->back()
            ->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
      }
   }
   
}
