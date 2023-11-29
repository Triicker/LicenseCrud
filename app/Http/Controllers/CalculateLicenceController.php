<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LogLicenceController;
use App\Models\Zwncoliglicenca;
use App\Models\Zwncliente;
use App\Models\Zwnproduto;
use App\Models\Zwncoligada;
use App\Models\Zwnusuempresa;
use App\Models\Zwnempresa;
use App\Models\Zwnloglicenca;
use JWTAuth;

use Illuminate\Http\Request;

class CalculateLicenceController extends Controller
{
    public function calcularLicenca(Request $request)
{
    
    try {
        $userName = null;
        $empresaNome = null;
        $idempresa = null;
        $idusuario = null;
        $requestData = $request->all();

        if ($request->is('api/*')) {
            list($userName, $idusuario, $idempresa) = $this->getUserInfoFromJWT();
        } else {
            list($userName, $idusuario, $idempresa) = $this->getUserInfoFromSession();
        }
        $IDCOLIGADA = $requestData['IDCOLIGADA'];
        $IDFILIAL = $requestData['IDFILIAL'];
        $IDTIPOCURSO = $requestData['IDTIPOCURSO'];
        $CNPJ = $requestData['CNPJ'];
        $NOMEPRODUTO = $requestData['NOMEPRODUTO'];
        $QTDALUNOS = $requestData['QTDALUNOS'];
        $QTDCHAMADAS = $requestData['QTDCHAMADAS'];
        $VERSAOTOTVS = $requestData['VERSAOTOTVS'];
        $VERSAOWORKNOW = $requestData['VERSAOWORKNOW'];

        $coligada = Zwncoligada::where('CGC', $CNPJ)
            ->where('IDCOLIGADA', $IDCOLIGADA)
            ->first();
            
        if ($coligada) {
          $produto = Zwnproduto::where('APELIDO', $NOMEPRODUTO)->first();
            $coliglicenca = Zwncoliglicenca::where('IDCLIENTE', $coligada->IDCLIENTE)
            ->where('IDCOLIGADA', $IDCOLIGADA)
            ->where('IDPRODUTO', $produto->IDPRODUTO)
            ->where('ATIVO', 1)
            ->first();

            if (!$produto) {
                $response = [
                    'status' => 'error',
                    'message' => 'Produto não encontrado na tabela Zwnproduto.',
                    'data' => null,
                ];
                return response()->json($response, 404);
            }

            $cliente = Zwncliente::find( $coligada->IDCLIENTE);
            $DTINICIO = $coliglicenca->DTINICIO;
            $DTFIM = $coliglicenca->DTFIM;
            $dataHoje = now();
            $liberadoAte = $this->calcularDataLiberacao($dataHoje, $DTINICIO, $DTFIM, 1, $cliente);
    
            $log = new Zwnloglicenca();
            $log->IDCLIENTE =  $coligada->IDCLIENTE;
            $log->IDCOLIGADA = $IDCOLIGADA;
            $log->IDPRODUTO =  $produto->IDPRODUTO;
            $log->IDFILIAL = $IDFILIAL;
            $log->IDTIPOCURSO = $IDTIPOCURSO;
            $log->IDEMPRESA = $idempresa;
            $log->IDUSUARIO = $idusuario;
            $log->QTDALUNOS = $QTDALUNOS;
            $log->QTDCHAMADAS = $QTDCHAMADAS;
            $log->VERSAOTOTVS = $VERSAOTOTVS;
            $log->VERSAOWORKNOW = $VERSAOWORKNOW;
            $log->LIBERADO = !empty($liberadoAte) ? 1 : 0;
            $log->LIBERADOATE = $liberadoAte;
            $log->RECCREATEDBY = $userName;
            $log->RECCREATEDON = now();
            
            $log->save();


        } else {
            $response = [
                'status' => 'error',
                'message' => 'Registro de licença não encontrado para este cliente.',
                'data' => null,
            ];
            return response()->json($response, 404);
        }

        $response = [
            'status' => ($liberadoAte != '') ? 'success' : 'error',
            'message' => ($liberadoAte != '') ? 'Cálculo de licença realizado com sucesso' : 'Licença expirada!',
            'data' => [
                'LIBERADO' => ($liberadoAte != '') ? 1 : 0,
                'LIBERADOATE' => $liberadoAte,
                'NOMEPRODUTO' => $produto->NOME,
            ],
        ];

        if ($response['data']['LIBERADO'] == 0) {
            return response()->json($response, 401);
        }

        return response()->json($response, 200);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao calcular a licença',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    }
}

private function calcularDataLiberacao($dataHoje, $DTINICIO, $DTFIM, $ATIVO, $cliente)
{
    if ($dataHoje >= $DTINICIO && $dataHoje <= $DTFIM && $ATIVO == 1) {
        $DIASLICENCA = $cliente->DIASLICENCA;
        $liberadoAte = $dataHoje->copy()->addDays($DIASLICENCA);

        if ($liberadoAte > $DTFIM) {
            $liberadoAte = $DTFIM;
        }

        return $liberadoAte;
    } else {
        return null;
    }
}

private function getUserInfoFromJWT() {
    $token = JWTAuth::getToken();
    $user = JWTAuth::toUser($token);

    $userName = $user->USUARIO;
    $idusuario = $user->IDUSUARIO;
    $empresa = Zwnusuempresa::where('IDUSUARIO', $user->IDUSUARIO)->first();
    $idempresa = $empresa ? $empresa->IDEMPRESA : null;

    return [$userName, $idusuario, $idempresa];
}

protected function getEmpresaID($user)
{
    $empresa = Zwnusuempresa::where('IDUSUARIO', $user->IDUSUARIO)->first();

    if ($empresa) {
        return $empresa->IDEMPRESA;
    }

    return null; 
}

}