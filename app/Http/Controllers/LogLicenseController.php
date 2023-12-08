<?php

namespace App\Http\Controllers;
use App\Models\Zwnloglicenca;
use App\Models\Zwnloglicencaitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LogLicenseController extends Controller
{
    public function index()
    {
        $logs = Zwnloglicenca::with(['empresa', 'usuario', 'cliente', 'produto'])
            ->whereIn('IDLOGLIC', function ($query) {
                $query->selectRaw('MAX(IDLOGLIC) IDLOGLIC')
                    ->from('zwnloglicenca')
                    ->groupBy('IDCLIENTE', 'IDCOLIGADA', 'IDFILIAL', 'IDTIPOCURSO', 'IDPRODUTO');
            })
            ->get();

        $logItens = Zwnloglicencaitem::with(['empresa', 'usuario', 'cliente', 'produto'])
            ->whereIn('IDLOGLIC', function ($query) {
                $query->selectRaw('IDLOGLIC')
                    ->from('zwnloglicencaitem')
                    ->where('IDLOGLIC', '2');
            })
            ->get();
            
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['logs' => $logs, 'logItens' => $logItens]);
        } else {
            return view('indexLog', compact('logs', 'logItens'));
        }
    }

    public function indexLog($IDCLIENTE, Request $request) 
    {
        try {
            $contatos = Zwnclicontato::where('IDCLIENTE', $IDCLIENTE)->with('cliente')->get();
            $clientes = Zwncliente::all();
    
            $cliente = $clientes->find($IDCLIENTE);
    
            if ($request->is('api/*') || $request->wantsJson()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Contatos do cliente recuperados com sucesso',
                    'data' => $contatos,
                ];
                return response()->json($response);
            } else {
                $data = ['contatos' => $contatos, 'clientes' => $clientes, 'cliente' => $cliente];
                return view('indexContact', compact('data'));
            }
        } catch (\Exception $e) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $response = [
                    'status' => 'error',
                    'message' => 'Erro ao recuperar contatos do cliente',
                    'data' => $e->getMessage(),
                ];
                return response()->json($response, 400);
            } else {
                return back()->withErrors(['error' => 'Erro ao recuperar contatos do cliente: ' . $e->getMessage()]);
            }
        }
    }

    public function logLicenca(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'IDCOLIGADA' => 'required|integer',
                'IDFILIAL' => 'required|integer',
                'IDEMPRESA' => 'required|integer',
                'IDTIPOCURSO' => 'required|integer',
                'CNPJ' => 'required|string',
                'USUARIO' => 'required|string',
                'NOMEPRODUTO' => 'required|string',
                'VERSAOTOTVS' => 'required|string',
                'VERSAOWORKNOW' => 'required|string'
            ]);
    
            $IDCOLIGADA = $validatedData['IDCOLIGADA'];
            $IDFILIAL = $validatedData['IDFILIAL'];
            $IDTIPOCURSO = $validatedData['IDTIPOCURSO'];
            $USUARIO = $validatedData['USUARIO'];
    
            $log = new Zwnloglicenca();
            $log->IDCOLIGADA = $IDCOLIGADA;
            $log->IDFILIAL = $IDFILIAL;
            $log->IDCODTIPOCURSO = $IDTIPOCURSO;
            $log->RECCREATEDBY = $USUARIO;
            $log->RECCREATEDON = now();
            $log->RECMODIFIEDON = now();
            $log->RECMODIFIEDBY = $USUARIO;
            $log->save();

            $response = [
                'status' => 'success',
                'message' => 'Log de licença criado com sucesso',
                'data' => [
                    'LIBERADO' => $liberado,
                    'LIBERADOATE' => $liberadoAte,
                ],
            ];
    
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao criar o log de licença',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }
    

}
