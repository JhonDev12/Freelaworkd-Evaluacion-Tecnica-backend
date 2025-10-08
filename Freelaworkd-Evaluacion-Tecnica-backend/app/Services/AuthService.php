<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Servicio de autenticación de usuarios.
 *
 * Responsable de registro, inicio y cierre de sesión.
 * Emite y revoca tokens personales usando Laravel Sanctum.
 */
class AuthService
{
    /**
     * Registra un nuevo usuario y devuelve su token.
     *
     * @param  array{name:string,email:string,password:string}  $data
     * @return array{user:\App\Models\User,token:string}
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => 3, 
        ]);
    
        $token = $user->createToken('api_token')->plainTextToken;
    
        return compact('user', 'token');
    }
    

    /**
     * Inicia sesión y devuelve un token para el usuario.
     *
     * @param  array{email:string,password:string}  $data
     * @return array{user:\App\Models\User,token:string}
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.'],
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return compact('user', 'token');
    }

    /**
     * Cierra la sesión revocando el token Bearer actual.
     *
     * No usa currentAccessToken() para evitar falsos positivos de análisis estático.
     * Busca el token por el valor del encabezado Authorization.
     */
    public function logout(): void
    {
        $bearer = request()->bearerToken();
        if (! $bearer) {
            return;
        }

        $accessToken = PersonalAccessToken::findToken($bearer);
        if (! $accessToken) {
            return;
        }

        // Seguridad: revoca solo si pertenece al usuario autenticado
        if ((int) $accessToken->tokenable_id === (int) Auth::id()
            && $accessToken->tokenable_type === User::class) {
            $accessToken->delete();
        }
    }
}
