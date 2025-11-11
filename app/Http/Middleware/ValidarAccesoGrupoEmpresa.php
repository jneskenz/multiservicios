<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\GrupoEmpresa;

class ValidarAccesoGrupoEmpresa
{
   /**
    * Verificar que el usuario tenga acceso al grupo empresarial
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
   public function handle(Request $request, Closure $next): Response
   {
      $user = $request->user();

      \Illuminate\Support\Facades\Log::info('[Middleware] ValidarAccesoGrupoEmpresa iniciado para: ' . $user->email);

      // Obtener slug del grupo desde la ruta
      $slugGrupo = $request->route('grupo');

      // dd($slugGrupo);

      if (!$slugGrupo) {
         abort(404, 'Grupo empresarial no especificado');
      }

      \Illuminate\Support\Facades\Log::info('[Middleware] Buscando grupo con slug: ' . $slugGrupo);

      // Buscar el grupo empresarial
      $grupo = GrupoEmpresa::where('slug', $slugGrupo)->first();

      if (!$grupo) {
         abort(404, 'Grupo empresarial no encontrado');
      }

      \Illuminate\Support\Facades\Log::info('[Middleware] Grupo encontrado: ' . $grupo->nombre);

      // Verificar que el grupo esté activo
      if (!$grupo->estaActivo()) {
         abort(403, 'El grupo empresarial está inactivo: ' . $grupo->estaActivo());
      }

      // Verificar si el plan ha expirado
      if ($grupo->planExpirado()) {
         return redirect()->route('grupo.plan-expirado', ['grupo' => $grupo->slug])
            ->with('error', 'El plan del grupo empresarial ha expirado');
      }

      // Superusuario tiene acceso total
      if ($user->esSuperusuario()) {
         $request->merge(['grupoEmpresa' => $grupo]);
         return $next($request);
      }

      // Verificar acceso del usuario al grupo
      if (!$user->tieneAccesoAGrupo($grupo->id)) {
         abort(403, 'No tienes acceso a este grupo empresarial');
      }

      \Illuminate\Support\Facades\Log::info('[Middleware] Acceso validado, compartiendo con vistas');

      // Inyectar el grupo en el request
      $request->merge(['grupoEmpresa' => $grupo]);

      // Compartir $grupoActual globalmente para todas las vistas
      view()->share('grupoActual', $grupo);

      \Illuminate\Support\Facades\Log::info('[Middleware] ValidarAccesoGrupoEmpresa completado');

      // Registrar acceso en activity log
      // activity()
      //    ->performedOn($grupo)
      //    ->causedBy($user)
      //    ->withProperties([
      //       'ip' => $request->ip(),
      //       'user_agent' => $request->userAgent(),
      //    ])
      //    ->log($user->name . ': Accedió al workspace de ' . $grupo->nombre);
      

      return $next($request);
   }
}
