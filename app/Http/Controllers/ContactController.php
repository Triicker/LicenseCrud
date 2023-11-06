<?php

namespace App\Http\Controllers;

use App\Models\Zwnclicontato;
use App\Models\Zwncliente;

use Illuminate\Http\Request;


class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contatos = Zwnclicontato::with('cliente')->get();
        $clientes = Zwncliente::all();

        $data = ['contatos' => $contatos, 'clientes' => $clientes];


        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json(['data' => $data]);
        } else {
            return view('indexContact', compact('data'));
        }
    }

    
    public function showContact(Request $request, $IDCONTATO)
    {
        $contato = Zwnclicontato::find($IDCONTATO);

        if (!$contato) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'Contato não encontrado'], 404);
            } else {
                return abort(404);
            }
        }

        if ($request->is('api/*')) {
            return response()->json(['data' => $contato]);
        } else {
            return view('showContact', compact('contato'));
        }
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'TELEFONE' => 'required|string|max:15',
            'CELULAR' => 'required|string|max:15',
            'EMAIL' => 'required|string|max:60',
            'ATIVO' => 'required|boolean',
            'CLIENTE' => 'required|exists:zwnclientes,IDCLIENTE',
        ]);
        
        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECCREATEDBY'] = 'ADMIN';
        $validatedData['RECMODIFIEDBY'] = 'ADMIN';
        $validatedData['IDCLIENTE'] = $request->input('CLIENTE');

        Zwnclicontato::create($validatedData);

        if ($request->is('api/*')) {
            return response()->json(['message' => 'Contato criado com sucesso']);
        } else {
            return redirect()->route('contatos.index')->with('success', 'Contato criado com sucesso.');
        }
    }
    
    public function create()
    {
        $contatos = Zwnclicontato::all();

        if (request()->is('api/*')) {
            return response()->json(['contatos' => $contatos]);
        } else {
            return view('createContact', compact('contatos'));
        }
    }
    
    public function edit($IDCONTATO)
    {
        $contato = Zwnclicontato::find($IDCONTATO);

        if (!$contato) {
            if (request()->is('api/*')) {
                return response()->json(['error' => 'Contato não encontrado'], 404);
            } else {
                abort(404);
            }
        }

        $clientes = Zwncliente::all();

        if (request()->is('api/*')) {
            return response()->json(['contato' => $contato, 'clientes' => $clientes]);
        } else {
            return view('editContact', compact('contato', 'clientes'));
        }
    }

    
    public function update(Request $request, $IDCONTATO)
    {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'TELEFONE' => 'required|string|max:15',
            'CELULAR' => 'required|string|max:15',
            'EMAIL' => 'required|string|max:60',
            'ATIVO' => 'required|boolean',
            'CLIENTE' => 'required|exists:zwnclientes,IDCLIENTE',
        ]);

        $contato = Zwnclicontato::find($IDCONTATO);

        if (!$contato) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'Contato não encontrado'], 404);
            } else {
                abort(404);
            }
        }

        $contato->update([
            'NOME' => $validatedData['NOME'],
            'APELIDO' => $validatedData['APELIDO'],
            'TELEFONE' => $validatedData['TELEFONE'],
            'CELULAR' => $validatedData['CELULAR'],
            'EMAIL' => $validatedData['EMAIL'],
            'ATIVO' => $validatedData['ATIVO'],
            'IDCLIENTE' => $validatedData['CLIENTE'],
        ]);

        if ($request->is('api/*')) {
            return response()->json(['message' => 'Contato atualizado com sucesso']);
        } else {
            return redirect()->route('contatos.index')->with('success', 'Contato atualizado com sucesso');
        }
    }
    
        
    public function delete(Request $request, $IDCONTATO)
    {
        $contato = Zwnclicontato::find($IDCONTATO);

        if (!$contato) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'Contato não encontrado'], 404);
            } else {
                abort(404);
            }
        }

        $contato->delete();

        if ($request->is('api/*')) {
            return response()->json(['message' => 'Contato excluído com sucesso']);
        } else {
            return redirect()->route('contatos.index')->with('success', 'Contato excluído com sucesso');
        }
    }
    
 
}
