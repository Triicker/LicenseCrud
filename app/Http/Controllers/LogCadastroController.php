<?php

namespace App\Http\Controllers;
use App\Models\Zwnusuario;
use App\Models\Zwnlogcadastro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LogCadastroController extends Controller
{
    public function index()
{
    $logs = Zwnlogcadastro::with(['empresa', 'usuario'])->get();

    if (request()->is('api/*') || request()->wantsJson()) {
        return response()->json(['logs' => $logs]);
    } else {
        return view('indexLog', compact('logs'));
    }
}

    public function logCadastro(Request $request)
    {
        $validatedData = $request->validate([
            'acao' => 'required|string',
            'cadastro' => 'required|string',
            'valor_anterior' => 'string|nullable',
            'valor_novo' => 'required|string',
        ]);

        $acao = $request->input('acao');
        $cadastro = $request->input('cadastro');
        $valorAnterior = $request->input('valor_anterior');
        $valorNovo = $request->input('valor_novo');

        $log = new Zwnlogcadastro();
        $log->IDUSUARIO = auth()->user()->IDUSUARIO; 
        $log->CADASTRO = $cadastro;
        $log->VALORANTERIOR = $valorAnterior;
        $log->VALORNOVO = $valorNovo;
        $log->RECCREATEDBY = auth()->user()->USUARIO; 
        $log->RECCREATEDON = now();
        $log->RECMODIFIEDON = now();
        $log->RECMODIFIEDBY = auth()->user()->USUARIO; 

        $log->save();

        return response('Registro de log criado com sucesso', 201);
    }
}
