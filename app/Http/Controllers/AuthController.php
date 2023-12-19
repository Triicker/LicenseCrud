<?php

namespace App\Http\Controllers;

use App\Models\Zwnempresalayout;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Models\Zwnusuario;
use App\Models\Zwnusuempresa;
use App\Models\Zwnempresa;
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

    $userName = $credentials['USUARIO'];

    $user = Zwnusuario::where('USUARIO', $userName)->first();

    if ($user) {
        $empresa = Zwnusuempresa::where('IDUSUARIO', $user->IDUSUARIO)->first();
        if ($empresa) {
            $empresaNome = Zwnempresa::where('IDEMPRESA', $empresa->IDEMPRESA)->value('NOME');
            $empresaID = $empresa->IDEMPRESA; 
        } else {
            $empresaNome = 'Empresa não encontrada';
            $empresaID = null; 
        }
    } else {
        $empresaNome = 'Usuário não encontrado';
        $empresaID = null; 
    }

    return response()->json(['token' => $token, 'userName' => $userName, 'empresaNome' => $empresaNome, 'empresaID' => $empresaID]);
}



public function loginWeb(Request $request)
{
    $credentials = $request->only(['USUARIO', 'SENHA']);
    $token = $this->authenticateToken('web', $credentials);

    if ($token === false) {
        return redirect()->route('index')->with(['error' => 'Credenciais inválidas']);
    }

    $user = Auth::user();

    if ($user) {
        $userName = $user->NOME; 
        $userLogin = $user->USUARIO; 
        $empresa = Zwnusuempresa::where('IDUSUARIO', $user->IDUSUARIO)->first();
        $layout = Zwnempresalayout::all();

        if ($empresa) {
            $empresaNome = Zwnempresa::where('IDEMPRESA', $empresa->IDEMPRESA)->value('NOME');
        } else {
            $empresaNome = 'Empresa não encontrada';
        }

        session(['userName' => $userName, 'layout' => $layout, 'userLogin' => $userLogin, 'empresaNome' => $empresaNome, 'IDEMPRESA' => $empresa->IDEMPRESA, 'IDUSUARIO' => $user->IDUSUARIO]);

        return redirect()->route('login');
    } else {
        return redirect()->route('index')->with(['session_expired' => true]);
    }
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
            'expires_in' => auth('api')->factory()->getTTL() * 20
        ]);
    }
}
