<?php

namespace App\Http\Controllers;

use App\Models\Zwnusuario;
use Illuminate\Http\Request;

class UserControllerAPI extends Controller
{
    public function index()
    {
        return Zwnusuario::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'USUARIO' => 'required|string|max:255|unique:zwnusuarios',
            'SENHA' => 'required|string|min:6',
            'EMAIL' => 'required|email|unique:zwnusuarios',
            'ATIVO' => 'required|boolean'
        ]);

        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECCREATEDBY'] = 'ADMIN';
        $validatedData['RECMODIFIEDBY'] = 'ADMIN';

        Zwnusuario::create($validatedData);
    
        return response(["Usuário cadastrado com sucesso!"]);
    }

    public function update(Request $request, $IDUSUARIO)
{
    $validatedData = $request->validate([
        'NOME' => 'required|string|max:255',
        'APELIDO' => 'required|string|max:255',
        'USUARIO' => 'required|string|max:255', 
        'ATIVO' => 'required|boolean',
    ]);

    $validatedData['RECCREATEDON'] = now();
        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECCREATEDBY'] = 'userAtual';
        $validatedData['RECMODIFIEDBY'] = 'userAtual';
    $usuario = Zwnusuario::find($IDUSUARIO);

    $usuario->update($validatedData);

    return response(["Usuário alterado com sucesso!"]);
}

public function delete($IDUSUARIO)
{
    $usuario = Zwnusuario::find($IDUSUARIO);

    if (!$usuario) {
        return response()->json(['error' => 'Usuário não encontrado!'], 404);
    }

    $usuario->delete();

    return response()->json(['message' => 'Usuário excluído com sucesso!']);
}


    
}
