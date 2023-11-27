<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zwnusuempresa;
use App\Models\Zwnempresa;
use App\Models\Zwnusuario;
use JWTAuth;

class UserCompanyControllerAPI extends Controller
{
    public function index(Request $request)
{
    $usuarioempresa = Zwnusuempresa::with('usuario', 'empresa')->get();
    $usuario = Zwnusuario::all(); 
    $empresa = Zwnempresa::all(); 

    $data = ['usuarioempresa' => $usuarioempresa, 'usuario' => $usuario, 'empresa' => $empresa];
    
    if ($request->is('api/*') || $request->wantsJson()) {
        return response()->json(['usuarioempresa' => $usuarioempresa]);
    } else {
        return view('indexClient', compact('data'));
    }
}

public function store(Request $request)
{
    $token = JWTAuth::getToken();
    $user = JWTAuth::toUser($token);
    $userName = $user->USUARIO;

    $validatedData = $request->validate([
        'IDUSUARIO' => 'required|integer',
        'IDEMPRESA' => 'required|integer',
        'ATIVO' => 'required|boolean',
    ]);

    $validatedData['RECCREATEDON'] = now();
    $validatedData['RECMODIFIEDON'] = now();
    $validatedData['RECCREATEDBY'] = $userName;
    $validatedData['RECMODIFIEDBY'] = $userName;

    $usuario = Zwnusuario::find($validatedData['IDUSUARIO']);
    $empresa = Zwnempresa::find($validatedData['IDEMPRESA']);

    if ($usuario && $empresa) {
        Zwnusuempresa::create($validatedData);

        return response(["Usuário de empresa cadastrado com sucesso!"]);
    } else {
        return response(["Usuário ou empresa não encontrados"], 404);
    }
}


    public function update(Request $request, $IDUSUARIOEMPRESA)
{
    $token = JWTAuth::getToken();
    $user = JWTAuth::toUser($token);
    $userName = $user->USUARIO;

    $validatedData = $request->validate([
        'ATIVO' => 'required|boolean',
    ]);

    $validatedData['RECMODIFIEDON'] = now();
    $validatedData['RECMODIFIEDBY'] = $userName;

    $registros = Zwnusuempresa::where('IDUSUARIOEMPRESA', $IDUSUARIOEMPRESA)->get();

    if ($registros->isEmpty()) {
        return response()->json(['error' => 'Nenhum registro encontrado para o IDUSUARIOEMPRESA especificado'], 404);
    }

    foreach ($registros as $registro) {
        $registro->update($validatedData);
    }

    return response()->json(['message' => 'Registros atualizados com sucesso']);
}


    public function delete($IDUSUARIOEMPRESA)
{
    $registro = Zwnusuempresa::where('IDUSUARIOEMPRESA', $IDUSUARIOEMPRESA)->first();

    if (!$registro) {
        return response()->json(['error' => 'Registro não encontrado'], 404);
    }

    $registro->delete();

    return response()->json(['message' => 'Usuário excluído com sucesso']);
}

}
