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
    // protected $redirectTo = '/home';

    /**
     * Redirigir usuarios después del login según su rol y empresa asignada
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        Log::info('Usuario autenticado: ' . $user->email);
        Log::info('Usuario isSuperAdmin: ' . $user->isSuperAdmin());

        // 🔹 Si es super_admin
        // if ($user->hasRole('super_admin')) { // con rol
        if ($user->isSuperAdmin()) { // sin rol
            Log::info('Usuario entro ==> ');

            return '/admin';
        }

        // 🔹 Si tiene empresa asignada
        if ($user->grupo_empresa && $user->grupo_empresa->slug) {
            Log::info('Usuario entro 2: ');

            return '/' . $user->grupo_empresa->slug . '/';
        }

        Log::info('Usuario entro 3: ');

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
