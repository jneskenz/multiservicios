<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Redirigir usuarios después del login según su rol y empresa asignada
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        Log::info('Usuario autenticado: ' . $user->email);

        // 🔹 Si es super_admin
        if ($user->isSuperAdmin()) {
            Log::info('Usuario es SuperAdmin, redirigiendo a /admin');
            return '/admin';
        }

        // 🔹 Si tiene grupo asignado (cargar SOLO el slug, SIN relaciones)
        if ($user->grupo_empresa_id) {
            Log::info('Obteniendo slug del grupo ID: ' . $user->grupo_empresa_id);
            
            $grupoSlug = \App\Models\GrupoEmpresa::where('id', $user->grupo_empresa_id)
                ->value('slug');

            Log::info('Grupo slug obtenido: ' . $grupoSlug);
            
            if ($grupoSlug) {
                Log::info('Usuario redirigido a grupo: ' . $grupoSlug);
                return '/' . $grupoSlug . '/';
            }
        }

        Log::info('Usuario sin grupo asignado, redirigiendo a /home');

        // 🔹 Si no tiene empresa asignada
        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
