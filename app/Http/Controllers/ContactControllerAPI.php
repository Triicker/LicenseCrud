<?php

namespace App\Http\Controllers;


use App\Models\Zwnclicontato;
use Illuminate\Http\Request;

class ContactControllerAPI extends Controller
{
    public function index()
    {
        return Zwnclicontato::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'IDCLIENTE' => 'required|integer',
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'TELEFONE' => 'required|string|max:15',
            'CELULAR' => 'required|string|max:15',
            'EMAIL' => 'required|string|max:60',
            'ATIVO' => 'required|boolean',
            'RECCREATEDBY' => 'required|string|max:255',
            'RECCREATEDON' => 'required|date',
            'RECMODIFIEDBY' => 'required|string|max:255',
            'RECMODIFIEDON' => 'required|date',
        ]);

        Zwnclicontato::create($validatedData);

        return response(["Contato cadastrado com sucesso!"]);
    }

    public function show($IDCONTATO)
    {
        $contato = Zwnclicontato::with('empresa')->find($IDCONTATO);

        if (!$contato) {
            return response()->json(['error' => 'Contato não encontrado'], 404);
        }

        return response()->json($contato);
    }

    public function update(Request $request, $IDCONTATO)
    {
        $validatedData = $request->validate([
            'IDCLIENTE' => 'required|integer',
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'TELEFONE' => 'required|string|max:15',
            'CELULAR' => 'required|string|max:15',
            'EMAIL' => 'required|string|max:60',
            'ATIVO' => 'required|boolean',
        ]);

        $contato = Zwnclicontato::find($IDCONTATO);

        if (!$contato) {
            return response()->json(['error' => 'Contato não encontrado'], 404);
        }

        $contato->update($validatedData);

        return response(["OK"]);
    }

    public function delete($IDCONTATO)
    {
        $contato = Zwnclicontato::find($IDCONTATO);

        if (!$contato) {
            return response()->json(['error' => 'Contato não encontrado'], 404);
        }

        $contato->delete();

        return response()->json(['message' => 'Contato excluído com sucesso']);
    }
}
