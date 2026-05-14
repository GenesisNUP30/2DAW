<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            // El uso de stateless() evita errores de coincidencia de sesión
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (empty($googleUser->email)) {
                return redirect()->route('login')->withErrors('Google no devolvió ningún correo.');
            }

            // 1. Buscar usuario existente
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if (!$user) {
                // 2. Crear usuario si no existe
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'provider'  => 'google',
                    'avatar'    => $googleUser->avatar,
                    'password'  => bcrypt(Str::random(24)), // Password aleatorio por seguridad
                    'tipo'      => 'operario',           // Usamos tu columna 'tipo'
                    'fecha_alta' => now(),               // Usamos tu columna 'fecha_alta'
                    'status'    => 'Active',
                ]);
            } else {
                // 3. Si existe pero no tenía google_id, lo vinculamos
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'provider'  => 'google',
                    ]);
                }
            }

            // Actualizamos el último login
            $user->update(['last_login' => now()]);

            Auth::login($user);

            // Redirige a tu SPA de clientes
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Error en Google Auth: ' . $e->getMessage());
            return redirect()->route('login')->withErrors('La autenticación falló. Revisa los logs.');
        }
    }
}
