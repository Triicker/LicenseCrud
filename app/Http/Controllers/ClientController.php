<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LogCadastroController;
use App\Models\Zwncliente;
use App\Models\Zwnempresa;
use App\Models\Zwnlogcadastro;
use App\Models\Zwnusuempresa;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index(Request $request)
{
    try {
        $clientes = Zwncliente::with('empresa')->get();
        $empresas = Zwnempresa::all(); 

        $data = ['clientes' => $clientes, 'empresas' => $empresas];
        
        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'success',
                'message' => 'Clientes recuperados com sucesso',
                'data' => $clientes,
            ];
            return response()->json($response);
        } else {
            return view('indexClient', compact('data'));
        }
    } catch (\Exception $e) {
        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar clientes',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao recuperar clientes: ' . $e->getMessage()]);
        }
    }
}

public function indexId($IDCLIENTE)
{
    try {
        $cliente = Zwncliente::find($IDCLIENTE);

        if (!$cliente) {
            $response = [
                'status' => 'error',
                'message' => 'Cliente não encontrado',
                'data' => null,
            ];
            return response()->json($response, 404);
        }

        $response = [
            'status' => 'success',
            'message' => 'Cliente recuperado com sucesso',
            'data' => $cliente,
        ];
        return response()->json($response);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao recuperar o cliente',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    }
}

public function indexCompany($IDEMPRESA, Request $request) 
{
    try {
        $clientes = Zwncliente::where('IDEMPRESA', $IDEMPRESA)->with('empresa')->get();
        $empresas = Zwnempresa::all();

        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'success',
                'message' => 'Clientes da empresa recuperados com sucesso',
                'data' => $clientes,
            ];
            return response()->json($response);
        } else {
            $data = ['clientes' => $clientes, 'empresas' => $empresas];
            return view('indexClient', compact('data'));
        }
    } catch (\Exception $e) {
        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar clientes da empresa',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao recuperar clientes da empresa: ' . $e->getMessage()]);
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
    $userName = null;
    $empresaNome = null;
    $idempresa = null;
    $idusuario = null;

    if ($request->is('api/*')) {
        list($userName, $idusuario, $idempresa) = $this->getUserInfoFromJWT();
    } else {
        list($userName, $idusuario, $idempresa) = $this->getUserInfoFromSession();
    }

    try {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'ATIVO' => 'required|boolean',
            'IDEMPRESA' => 'integer',
        ]);

        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECCREATEDBY'] = $userName;

        if ($request->is('api/*')) {
            $validatedData['IDEMPRESA'] = $validatedData['IDEMPRESA'];
        } else {
            $validatedData['IDEMPRESA'] = is_null($idempresa) ? null : (int)$idempresa;
        }

        Zwncliente::create($validatedData);

        $logData = [
            'CADASTRO' => 'Cliente criado: ' . $validatedData['NOME'],
            'VALORANTERIOR' => null,
            'VALORNOVO' => json_encode($validatedData),
            'RECCREATEDBY' => $userName,
            'RECCREATEDON' => now(),
            'IDUSUARIO' => $idusuario,
            'IDEMPRESA' => $idempresa,
        ];

        $this->createLog($logData, $request);

        if ($request->is('api/*')) {
            return $this->createApiResponse($validatedData, 201);
        } else {
            return $this->createWebResponse('Cliente criado com sucesso!');
        }
    } catch (\Exception $e) {
        return $this->handleError($e, $request);
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
    $idusuario = session('IDUSUARIO');
    $idempresa = session('IDEMPRESA');

    return [$userName, $idusuario, $idempresa];
}

private function createLog($logData, $request) {
    $log = new Zwnlogcadastro();
    $log->fill($logData);
    $log->save();
}

private function createApiResponse($data, $statusCode) {
    $response = [
        'status' => 'success',
        'message' => 'Cliente criado com sucesso!',
        'data' => $data,
    ];
    return response()->json($response, $statusCode);
}

private function createWebResponse($message) {
    return redirect()->route('clientes.index')->with('success', $message);
}

private function handleError($e, $request) {
    if ($request->is('api/*')) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao criar o cliente',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    } else {
        return back()->withInput()->withErrors(['error' => 'Erro ao criar o cliente: ' . $e->getMessage()]);
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
        
        try {
            $validatedData = $request->validate([
                'NOME' => 'string|max:255',
                'APELIDO' => 'string|max:255',
                'ATIVO' => 'boolean',
            ]);
    
            $cliente = Zwncliente::find($IDCLIENTE);
    
            if (!$cliente) {
                if ($request->is('api/*')) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Cliente não encontrado',
                        'data' => null,
                    ];
                    return response()->json($response, 404);
                } else {
                    abort(404);
                }
            }
    
            list($userName, $idusuario, $idempresa) = $this->getUserInfoForUpdate($request);
    
            $validatedData['RECMODIFIEDON'] = now();
            $validatedData['RECMODIFIEDBY'] = $userName;
    
            $clienteAntesDaAtualizacao = $cliente->toArray();
    
            $cliente->update($validatedData);
    
            $clienteDepoisDaAtualizacao = $cliente->toArray();
    
            $logData = [
                'IDUSUARIO' => $idusuario,
                'CADASTRO' => 'Cliente atualizado: ' . $cliente->NOME,
                'VALORANTERIOR' => json_encode($clienteAntesDaAtualizacao),
                'VALORNOVO' => json_encode($clienteDepoisDaAtualizacao),
                'RECCREATEDBY' => $userName,
                'RECCREATEDON' => now(),
                'RECMODIFIEDBY' => $userName,
                'RECMODIFIEDON' => now(),
                'IDEMPRESA' => $idempresa,
            ];
    
            $this->createLog($logData, $request);
    
            if ($request->is('api/*')) {
                $response = [
                    'status' => 'success',
                    'message' => 'Cliente atualizado com sucesso',
                    'data' => $cliente,
                ];
                return response()->json($response);
            } else {
                return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso');
            }
        } catch (\Exception $e) {
            return $this->handleError($e, $request);
        }
    }
    
    private function getUserInfoForUpdate($request) {
        if ($request->is('api/*')) {
            list($userName, $idusuario, $idempresa) = $this->getUserInfoFromJWT();
        } else {
            list($userName, $idusuario, $idempresa) = $this->getUserInfoFromSession();
        }
    
        return [$userName, $idusuario, $idempresa];
    }
    


public function delete($IDCLIENTE)
{
    try {
        $cliente = Zwncliente::find($IDCLIENTE);

        if (!$cliente) {
            if (request()->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Cliente não encontrado',
                    'data' => null,
                ];
                return response()->json($response, 404);
            } else {
                abort(404);
            }
        }

        $cliente->delete();

        if (request()->is('api/*')) {
            $response = [
                'status' => 'success',
                'message' => 'Cliente excluído com sucesso',
                'data' => null, 
            ];
            return response()->json($response);
        } else {
            return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso');
        }
    } catch (\Exception $e) {
        if (request()->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao excluir o cliente',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao excluir o cliente: ' . $e->getMessage()]);
        }
    }
}

    

}
