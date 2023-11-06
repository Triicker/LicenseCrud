<?php

namespace App\Http\Controllers;

use App\Models\Zwnempresa;
use Illuminate\Http\Request;


class CompanyControllerAPI extends Controller
{
    public function index()
    {
        return Zwnempresa::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'ATIVO' => 'required|boolean',
            'RECCREATEDBY' => 'required|string|max:255',
            'RECCREATEDON' => 'required|date',
            'RECMODIFIEDBY' => 'required|string|max:255',
            'RECMODIFIEDON' => 'required|date',
        ]);

        Zwnempresa::create($validatedData);

        return response(["Empresa cadastrada com sucesso!"]);
    }

    public function update(Request $request, $IDEMPRESA)
    {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'ATIVO' => 'required|boolean',
            'RECCREATEDBY' => 'required|string|max:255',
            'RECCREATEDON' => 'required|date',
            'RECMODIFIEDBY' => 'required|string|max:255',
            'RECMODIFIEDON' => 'required|date',
        ]);

        $empresa = Zwnempresa::find($IDEMPRESA);

        if (!$empresa) {
            return response()->json(['error' => 'Empresa não encontrada'], 404);
        }

        $empresa->update($validatedData);

        return response(["OK"]);
    }

    public function delete($IDEMPRESA)
    {
        $empresa = Zwnempresa::find($IDEMPRESA);

        if (!$empresa) {
            return response()->json(['error' => 'Empresa não encontrada'], 404);
        }

        $empresa->delete();

        return response()->json(['message' => 'Empresa excluída com sucesso']);
    }
}
