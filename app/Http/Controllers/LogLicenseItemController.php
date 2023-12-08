<?php

namespace App\Http\Controllers;
use App\Models\Zwnloglicenca;
use App\Models\Zwnloglicencaitem;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LogLicenseItemController extends Controller
{
    public function index()
    {
        $logs = Zwnloglicencaitem::with(['IDLOGLICITM'])->whereIn('IDLOGLICITM', function ($query) {
                $query->selectRaw('MAX(IDLOGLICITM)')
                    ->from('zwnloglicencaitem');
            })
            ->get();

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['logs' => $logs]);
        } else {
            return view('indexLog', compact('logs'));
        }
    }

    public function logLicencaItem(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'IDLOGLIC' => 'required|integer',
                'INFO' => 'required|string',
                'VALOR' => 'required|string',
            ]);
            $IDLOGLIC = $validatedData['IDLOGLIC'];
            $INFO = $validatedData['INFO'];
            $VALOR = $validatedData['VALOR'];
            $USUARIO = $validatedData['USUARIO'];

            $log = new Zwnloglicencaitem();
            $log->IDLOGLIC = $IDLOGLIC;
            $log->INFO = $INFO;
            $log->VALOR = $VALOR;
            $log->RECCREATEDBY = $USUARIO;
            $log->RECCREATEDON = now();
            $log->RECMODIFIEDON = now();
            $log->RECMODIFIEDBY = $USUARIO;
            $log->save();
            
            $response = [
                'status' => 'success',
                'message' => 'Log do item de licença criado com sucesso',
                'data' => [],
            ];
    
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao criar o log do item de licença',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }
    

}
