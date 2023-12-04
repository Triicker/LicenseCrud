<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogCadastroController;
use App\Models\Zwncoligada;
use App\Models\Zwncliente;
use App\Models\Zwnlogcadastro;
use App\Models\Zwnusuempresa;
use App\Models\Zwnempresa;
use App\Models\Zwncoliglicenca;

use JWTAuth;

use Illuminate\Http\Request;


class ColigadaController extends Controller
{
    public function index(Request $request)
{
    try {
        $coligadas = Zwncoligada::with('cliente', 'licenca')->get();
        $clientes = Zwncliente::all();
        $licencas = Zwncoliglicenca::all();

        $data = ['coligadas' => $coligadas, 'clientes' => $clientes, 'licencas' => $licencas];

        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'success',
                'message' => 'Coligadas recuperadas com sucesso',
                'data' => $coligadas,
            ];
            return response()->json($response);
        } else {
            return view('indexColigada', compact('data'));
        }
    } catch (\Exception $e) {
        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar coligadas',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao recuperar coligadas: ' . $e->getMessage()]);
        }
    }
}


public function indexId($IDCOLIGADA)
{
    try {
        $coligada = Zwncoligada::find($IDCOLIGADA);

        if (!$coligada) {
            $response = [
                'status' => 'error',
                'message' => 'Coligada não encontrada',
                'data' => null,
            ];
            return response()->json($response, 404);
        }

        $response = [
            'status' => 'success',
            'message' => 'Coligada recuperada com sucesso',
            'data' => $coligada,
        ];
        return response()->json($response);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao recuperar a coligada',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    }
}


public function indexClient($IDCLIENTE, Request $request)
{
    try {
        $coligadas = Zwncoligada::where('IDCLIENTE', $IDCLIENTE)->with('cliente')->get();
        $clientes = Zwncliente::all();
        $cliente = $clientes->find($IDCLIENTE);

        $data = ['coligadas' => $coligadas, 'clientes' => $clientes];

        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'success',
                'message' => 'Coligadas do cliente recuperadas com sucesso',
                'data' => $coligadas,
            ];
            return response()->json($response);
        } else {
            $data = ['coligadas' => $coligadas, 'clientes' => $clientes, 'cliente' => $cliente];
            return view('indexColigada', compact('data'));
        }
    } catch (\Exception $e) {
        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar coligadas do cliente',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao recuperar coligadas do cliente: ' . $e->getMessage()]);
        }
    }
}

protected function getEmpresaID($user)
{
    $empresa = Zwnusuempresa::where('IDUSUARIO', $user->IDUSUARIO)->first();

    if ($empresa) {
        return $empresa->IDEMPRESA;
    }

    return null; 
}

public function store(Request $request)
{
    try {
        $userName = null;
        $empresaNome = null;
        $userLogin = null;
        
        $idempresa = null;
        $idusuario = null;

        if ($request->is('api/*')) {
            list($userName, $idusuario, $idempresa) = $this->getUserInfoFromJWT();
        } else {
            list($userName, $userLogin,  $idusuario, $idempresa) = $this->getUserInfoFromSession();
        }

        $validatedData = $request->validate([
            'IDCOLIGADA' => 'integer',
            'NOME' => 'string|max:255',
            'APELIDO' => 'string|max:255',
            'IDCLIENTE' => 'integer',
            'IDIMAGEM' => 'integer|max:11',
            'NOMEFANTASIA' => 'string|max:255',
            'CGC' => 'string|max:255',
            'TELEFONE' => 'string|max:15',
            'CELULAR' => 'string|max:15',
            'EMAIL' => 'string|max:60',
            'ATIVO' => 'boolean',
        ]);

        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECCREATEDBY'] = $request->is('api/*') ? $userName : $userLogin;
        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECMODIFIEDBY'] = $request->is('api/*') ? $userName : $userLogin;

        $validatedData['IDCLIENTE'] = $request->is('api/*') ? $validatedData['IDCLIENTE'] : $request->input('CLIENTE');


        $validatedData['IDCOLIGADA'] = $request->input('IDCOLIGADA');


        $logData = [
            'IDUSUARIO' => $idusuario,
            'CADASTRO' => 'Coligada criada: ' . $request->input('IDCOLIGADA'),
            'VALORANTERIOR' => null,
            'VALORNOVO' => json_encode($validatedData),
            'RECCREATEDBY' => $request->is('api/*') ? $userName : $userLogin,
            'RECCREATEDON' => now(),
            'RECMODIFIEDON' => now(),
            'RECMODIFIEDBY' => $request->is('api/*') ? $userName : $userLogin,
            'IDEMPRESA' => $idempresa,
        ];
        $this->createLog($logData, $request);
        if ($request->is('api/*')) {
            $response = [
                'status' => 'success',
                'message' => 'Coligada criada com sucesso',
                'data' => ['id' => $request->input('IDCOLIGADA')],
            ];
            return response()->json($response, 201);
        } else {
            dd($request);
            return redirect()->route('coligadas.index', ['IDCOLIGADA' => $request->input('IDCOLIGADA')])->with('success', 'Coligada criada com sucesso.');
        }
    } catch (\Exception $e) {
        if ($request->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao criar a coligada',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withInput()->withErrors(['error' => 'Erro ao criar a coligada: ' . $e->getMessage()]);
        }
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

private function getUserInfoFromSession() {
    $userName = session('userName');
    $userLogin = Session('userLogin');
    $idusuario = session('IDUSUARIO');
    $idempresa = session('IDEMPRESA');

    return [$userName, $userLogin, $idusuario, $idempresa];
}

private function createLog($logData, $request) {
    $log = new Zwnlogcadastro();
    $log->fill($logData);
    $log->save();
}

    public function create()
    {
        $coligadas = Zwncoligada::all();

        if (request()->is('api/*')) {
            return response()->json(['coligadas' => $coligadas]);
        } else {
            return view('createColigada', compact('coligadas'));
        }
    }
    
    public function edit($IDCOLIGADA)
    {
        $coligada = Zwncoligada::find($IDCOLIGADA);

        if (!$coligada) {
            if (request()->is('api/*')) {
                return response()->json(['error' => 'Coligada não encontrado'], 404);
            } else {
                abort(404);
            }
        }

        $clientes = Zwncliente::all();
 
        if (request()->is('api/*')) {
            return response()->json(['coligada' => $coligada, 'clientes' => $clientes]);
        } else {
            return view('editColigada', compact('coligada', 'clientes'));
        }
    }

    
    public function update(Request $request, $IDCOLIGADA)
    {
        try {
            $validatedData = $request->validate([
                'NOME' => 'string|max:255',
                'APELIDO' => 'string|max:255',
                'TELEFONE' => 'string|max:15',
                'CELULAR' => 'string|max:15',
                'EMAIL' => 'string|max:60',
                'ATIVO' => 'boolean',
                'CLIENTE' => 'exists:zwnclientes,IDCLIENTE',
            ]);
    
            $coligada = Zwncoligada::find($IDCOLIGADA);
    
            if (!$coligada) {
                if ($request->is('api/*')) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Coligada não encontrada',
                        'data' => null,
                    ];
                    return response()->json($response, 404);
                } else {
                    abort(404);
                }
            }
    
            $coligadaAntesDaAtualizacao = $coligada->toArray();
    
            $coligada->update($validatedData);
    
            $coligadaDepoisDaAtualizacao = $coligada->toArray();
    
            list($userName, $userLogin, $idusuario, $idempresa) = $this->getUserInfoForUpdate($request);
    
            $logData = [
                'IDUSUARIO' => $idusuario,
                'CADASTRO' => 'Coligada atualizada: ' . $coligada->NOME,
                'VALORANTERIOR' => json_encode($coligadaAntesDaAtualizacao),
                'VALORNOVO' => json_encode($coligadaDepoisDaAtualizacao),
                'RECMODIFIEDBY' => $request->is('api/*') ? $userName : $userLogin,
                'RECMODIFIEDON' => now(),
                'IDEMPRESA' => $idempresa,
            ];
    
            $this->createLog($logData, $request);
    
            if ($request->is('api/*')) {
                $response = [
                    'status' => 'success',
                    'message' => 'Coligada atualizada com sucesso',
                    'data' => $coligada,
                ];
                return response()->json($response);
            } else {
                return redirect()->route('coligadas.cliente', ['IDCOLIGADA' => $IDCOLIGADA])->with('success', 'Coligada atualizada com sucesso');
            }
        } catch (\Exception $e) {
            if ($request->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Erro ao atualizar a coligada',
                    'data' => $e->getMessage(),
                ];
                return response()->json($response, 400);
            } else {
                return back()->withErrors(['error' => 'Erro ao atualizar a coligada: ' . $e->getMessage()]);
            }
        }
    }
    
    private function getUserInfoForUpdate($request) {
        if ($request->is('api/*')) {
            list($userName, $idusuario, $idempresa) = $this->getUserInfoFromJWT();
        } else {
            list($userName, $userLogin, $idusuario, $idempresa) = $this->getUserInfoFromSession();
        }
    
        return [$userName, $userLogin, $idusuario, $idempresa];
    }
    

    
        
public function delete(Request $request, $IDCOLIGADA)
{
    try {
        $coligada = Zwncoligada::find($IDCOLIGADA);

        if (!$coligada) {
            if ($request->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Coligada não encontrada',
                    'data' => null,
                ];
                return response()->json($response, 404);
            } else {
                abort(404);
            }
        }

         $coligadas = Zwncoligada::where('IDCLIENTE', $coligada->IDCLIENTE)->count();

        if ($coligadas > 0) {
            if (request()->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Não é possível excluir o cliente. Existem coligadas associadas.',
                    'data' => null,
                ];
                return response()->json($response, 400);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Não é possível excluir o cliente. Existem coligadas associadas.',
                    'data' => null,
                ];
                return response()->json($response, 400);
            }
        }

        $coligada->delete();

        if ($request->is('api/*')) {
            $response = [
                'status' => 'success',
                'message' => 'Coligada excluída com sucesso',
                'data' => null, 
            ];
            return response()->json($response);
        } else {
            return redirect()->route('coligadas.index')->with('success', 'Coligada excluída com sucesso');
        }
    } catch (\Exception $e) {
        if ($request->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao excluir a coligada',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao excluir a coligada: ' . $e->getMessage()]);
        }
    }
}

    
 
}
