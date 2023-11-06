<?php

namespace App\Http\Controllers;

use App\Models\Zwncliente;
use App\Models\Zwnempresa;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user(); 
    $clientes = Zwncliente::with('empresa')->get();
    $empresas = Zwnempresa::all();

    $data = ['clientes' => $clientes, 'empresas' => $empresas, 'user' => $user];

    if ($request->is('api/*') || $request->wantsJson()) {
        return response()->json(['data' => $data]);
    } else {
        return view('indexClient', compact('data'));
    }
}


    
    public function show(Request $request, $IDCLIENTE)
    {
        $cliente = Zwncliente::find($IDCLIENTE);

        if (!$cliente) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'Cliente não encontrado'], 404);
            } else {
                return abort(404);
            }
        }

        if ($request->is('api/*')) {
            return $cliente;
        } else {
            return view('showClient', compact('cliente'));
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'ATIVO' => 'required|boolean',
            'EMPRESA' => 'required|exists:zwnempresa,IDEMPRESA',
        ]);

        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECCREATEDBY'] = 'ADMIN';
        $validatedData['RECMODIFIEDBY'] = 'ADMIN';
        $validatedData['IDEMPRESA'] = $request->input('EMPRESA');

        Zwncliente::create($validatedData);

        if ($request->is('api/*')) {
            return response(["Cliente criado com sucesso!"]);
        } else {
            return redirect()->route('clientes.index')->with('success', 'Cliente criado com sucesso!');
        }
    }

    public function create()
{
    $empresas = Zwnempresa::all();

    if (request()->is('api/*')) {
        return response()->json(['empresas' => $empresas]);
    } else {
        return view('createClient', compact('empresas'));
    }
}


    public function edit($IDCLIENTE)
    {
        $cliente = Zwncliente::find($IDCLIENTE);

        if (!$cliente) {
            if (request()->is('api/*')) {
                return response()->json(['error' => 'Cliente não encontrado'], 404);
            } else {
                abort(404);
            }
        }

        $empresas = Zwnempresa::all();

        if (request()->is('api/*')) {
            return response()->json(['cliente' => $cliente, 'empresas' => $empresas]);
        } else {
            return view('editClient', compact('cliente', 'empresas'));
        }
    }

    public function update(Request $request, $IDCLIENTE)
    {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'ATIVO' => 'required|boolean',
            'EMPRESA' => 'required|exists:zwnempresa,IDEMPRESA',
        ]);

        $cliente = Zwncliente::find($IDCLIENTE);

        if (!$cliente) {
            if (request()->is('api/*')) {
                return response()->json(['error' => 'Cliente não encontrado'], 404);
            } else {
                abort(404);
            }
        }

        $cliente->update([
            'NOME' => $validatedData['NOME'],
            'APELIDO' => $validatedData['APELIDO'],
            'ATIVO' => $validatedData['ATIVO'],
            'IDEMPRESA' => $validatedData['EMPRESA'],
        ]);

        if (request()->is('api/*')) {
            return response()->json(['message' => 'Cliente atualizado com sucesso']);
        } else {
            return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso');
        }
    }

    public function delete($IDCLIENTE)
    {
        $cliente = Zwncliente::find($IDCLIENTE);
    
        if (!$cliente) {
            if (request()->is('api/*')) {
                return response()->json(['error' => 'Cliente não encontrado'], 404);
            } else {
                abort(404);
            }
        }
    
        $cliente->delete();
    
        if (request()->is('api/*')) {
            return response()->json(['message' => 'Cliente excluído com sucesso']);
        } else {
            return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso');
        }
    }
    

}
