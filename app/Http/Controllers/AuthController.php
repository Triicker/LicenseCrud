<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Models\Zwnusuario;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['USUARIO', 'SENHA']);
        $token = $this->authenticateToken('api', $credentials);

        if ($token === false) {
            return response()->json(['error' => 'Credenciais inválidas', 'credentials' => $credentials], 401);
        }
    
        return $this->respondWithToken($token);
    }

    public function loginWeb(Request $request)
    {
        $credentials = $request->only(['USUARIO', 'SENHA']);
        $token = $this->authenticateToken('web', $credentials);

        if ($token === false) {
            return redirect()->route('index')->with(['error' => 'Credenciais inválidas']);
        }

        $user = Auth::user();
        $userName = $user->NOME; 

        return redirect()->route('login')->with(['user' => $user, 'userName' => $userName]);
    }

    private function authenticateToken($guard, $credentials)
    {
        if ($token = auth($guard)->attempt([
            'USUARIO' => $credentials['USUARIO'],
            'password' => $credentials['SENHA']
        ])) {
            return $token;
        }

        return false;
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('index'); 
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
