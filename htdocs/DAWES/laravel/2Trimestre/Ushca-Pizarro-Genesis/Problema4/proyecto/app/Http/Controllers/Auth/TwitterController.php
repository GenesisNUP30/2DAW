<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TwitterController extends Controller
{
    /**
     * Redirige al usuario a la página de autorización de X (Twitter).
     */
    public function redirect()
    {
        // Importante: Usamos 'twitter-oauth-2' para la API moderna de X
        return Socialite::driver('twitter-oauth-2')->redirect();
    }

    /**
     * Maneja la respuesta de X (Twitter).
     */
    public function callback()
    {
        try {
            // Obtenemos los datos del usuario de Twitter
            $twitterUser = Socialite::driver('twitter-oauth-2')->stateless()->user();

            if (empty($twitterUser->email)) {
                return redirect()->route('login')
                    ->withErrors('Twitter no devolvió un correo electrónico. Revisa los permisos de tu App en el portal de X.');
            }

            // 1. Buscamos si el usuario ya existe por twitter_id o por email
            $user = User::where('twitter_id', $twitterUser->id)
                ->orWhere('email', $twitterUser->email)
                ->first();

            if (!$user) {
                // 2. Si no existe, lo creamos con tus columnas específicas
                $user = User::create([
                    'name'      => $twitterUser->name,
                    'email'     => $twitterUser->email,
                    'twitter_id' => $twitterUser->id,
                    'provider'  => 'twitter',
                    'avatar'    => $twitterUser->avatar,
                    'password'  => bcrypt(Str::random(24)), // Password aleatorio
                    'tipo'      => 'operario',           // Tu columna enum
                    'fecha_alta' => now(),               // Tu columna de fecha
                    'status'    => 'Active',
                ]);
            } else {
                // 3. Si existe pero no tenía vinculado Twitter, lo actualizamos
                if (!$user->twitter_id) {
                    $user->update([
                        'twitter_id' => $twitterUser->id,
                        'provider'   => 'twitter',
                    ]);
                }
            }

            // Actualizamos la fecha del último login
            $user->update([
                'last_login' => now(),
            ]);

            // Iniciamos sesión manualmente
            Auth::login($user);

            // Redirigimos a la ruta de tu aplicación
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Twitter OAuth Error: ' . $e->getMessage());

            return redirect()->route('login')
                ->withErrors('La autenticación con Twitter falló. Inténtalo de nuevo.');
        }
    }
}
