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

        $IDCLIENTE = $requestData['IDCLIENTE'];
        $IDCOLIGADA = $requestData['IDCOLIGADA'];
        $IDPRODUTO = $requestData['IDPRODUTO'];
        $IDFILIAL = $requestData['IDFILIAL'];
        $IDTIPOCURSO = $requestData['IDTIPOCURSO'];
        $ATIVO = $requestData['ATIVO'];
        $CNPJ = $requestData['CNPJ'];
        $USUARIO = $requestData['USUARIO'];
        $NOMEPRODUTO = $requestData['NOMEPRODUTO'];
        $QTDALUNOS = $requestData['QTDALUNOS'];
        $QTDCHAMADAS = $requestData['QTDCHAMADAS'];
        $VERSAOTOTVS = $requestData['VERSAOTOTVS'];
        $VERSAOWORKNOW = $requestData['VERSAOWORKNOW'];

        $coliglicenca = Zwncoliglicenca::where('IDCLIENTE', $IDCLIENTE)
            ->where('IDCOLIGADA', $IDCOLIGADA)
            ->where('IDPRODUTO', $IDPRODUTO)
            ->where('ATIVO', $ATIVO)
            ->first();

        if ($coliglicenca) {
            $produto = Zwnproduto::find($IDPRODUTO);

            if (!$produto) {
                $response = [
                    'status' => 'error',
                    'message' => 'Produto não encontrado na tabela Zwnproduto.',
                    'data' => null,
                ];
                return response()->json($response, 404);
            }

            $cliente = Zwncliente::find($IDCLIENTE);

            $DTINICIO = $coliglicenca->DTINICIO;
            $DTFIM = $coliglicenca->DTFIM;

            $dataHoje = now();
            $liberadoAte = $this->calcularDataLiberacao($dataHoje, $DTINICIO, $DTFIM, $ATIVO, $cliente);

            $log = new Zwnloglicenca();
            $log->IDCLIENTE = $IDCLIENTE;
            $log->IDCOLIGADA = $IDCOLIGADA;
            $log->IDPRODUTO = $IDPRODUTO;
            $log->IDFILIAL = $IDFILIAL;
            $log->IDTIPOCURSO = $IDTIPOCURSO;
            $log->IDEMPRESA = $idempresa;
            $log->IDUSUARIO = $idusuario;
            $log->CNPJ = $CNPJ;
            $log->USUARIO = $USUARIO;
            $log->NOMEPRODUTO = $NOMEPRODUTO;
            $log->QTDALUNOS = $QTDALUNOS;
            $log->QTDCHAMADAS = $QTDCHAMADAS;
            $log->VERSAOTOTVS = $VERSAOTOTVS;
            $log->VERSAOWORKNOW = $VERSAOWORKNOW;
            $log->LIBERADO = $ATIVO;
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
            'status' => ($liberadoAte != '') ? 'success' : 'unauthorized',
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
        return '';
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