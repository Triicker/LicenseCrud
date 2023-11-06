<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zwnusuempresa;

class UserCompanyControllerAPI extends Controller
{
    public function index()
    {
        return Zwnusuempresa::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'IDUSUARIO' => 'required|integer',
            'IDEMPRESA' => 'required|integer',
            'ATIVO' => 'required|boolean',
            'RECCREATEDBY' => 'required|string|max:255',
            'RECCREATEDON' => 'required|date',
            'RECMODIFIEDBY' => 'required|string|max:255',
            'RECMODIFIEDON' => 'required|date',
        ]);

        Zwnusuempresa::create($validatedData);

        return response(["Usuário de empresa cadastrado com sucesso!"]);
    }

    public function update(Request $request, $IDUSUARIO)
{
    $validatedData = $request->validate([
        'ATIVO' => 'required|boolean',
        'RECCREATEDBY' => 'required|string|max:255',
        'RECCREATEDON' => 'required|date',
        'RECMODIFIEDBY' => 'required|string|max:255',
        'RECMODIFIEDON' => 'required|date',
    ]);

    $registros = Zwnusuempresa::where('IDUSUARIO', $IDUSUARIO)->get();

    if ($registros->isEmpty()) {
        return response()->json(['error' => 'Nenhum registro encontrado para o IDUSUARIO especificado'], 404);
    }

    foreach ($registros as $registro) {
        $registro->update($validatedData);
    }

    return response()->json(['message' => 'Registros atualizados com sucesso']);
}


    public function delete($IDUSUARIO)
{
    $registro = Zwnusuempresa::where('IDUSUARIO', $IDUSUARIO)->first();

    if (!$registro) {
        return response()->json(['error' => 'Registro não encontrado'], 404);
    }

    $registro->delete();

    return response()->json(['message' => 'Usuário excluído com sucesso']);
}

}
