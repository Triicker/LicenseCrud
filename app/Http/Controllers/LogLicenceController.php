<?php

namespace App\Http\Controllers;
use App\Models\Zwnusuario;
use App\Models\Zwnloglicenca;
use App\Models\Zwncoligada;
use App\Models\Zwncoliglicenca;
use App\Models\Zwnempresa;
use App\Models\Zwncliente;
use App\Models\Zwnproduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LogLicenceController extends Controller
{
    public function index()
    {
        $logs = Zwnloglicenca::with(['empresa', 'usuario'])->get();
    
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['logs' => $logs]);
        } else {
            return view('indexLog', compact('logs'));
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
                'QTDALUNOS' => 'required|integer',
                'QTDCHAMADAS' => 'required|integer',
                'VERSAOTOTVS' => 'required|string',
                'VERSAOWORKNOW' => 'required|string',
            ]);
    
            $IDCOLIGADA = $validatedData['IDCOLIGADA'];
            $IDFILIAL = $validatedData['IDFILIAL'];
            $IDTIPOCURSO = $validatedData['IDTIPOCURSO'];
            $USUARIO = $validatedData['USUARIO'];
            $NOMEPRODUTO = $validatedData['NOMEPRODUTO'];
    
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
