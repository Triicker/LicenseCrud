<?php

namespace App\Http\Controllers;
use App\Models\Zwnclicontato;
use App\Models\Zwncliente;
use App\Models\Zwnusuempresa;
use App\Models\Zwnlogcadastro;
use JWTAuth;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
{
    try {
        list($userName, $idusuario, $idempresa) = $this->getUserInfoFromSession();

        $contatos = Zwnclicontato::whereHas('cliente', function ($query) use ($idempresa) {
            $query->where('IDEMPRESA', $idempresa);
        })->with('cliente')->get();

        $clientes = Zwncliente::all(); 

        $data = ['contatos' => $contatos, 'clientes' => $clientes];

        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'success',
                'message' => 'Contatos recuperados com sucesso',
                'data' => $contatos,
            ];
            return response()->json($response);
        } else {
            return view('indexContact', compact('data'));
        }
    } catch (\Exception $e) {
        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar contatos',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao recuperar contatos: ' . $e->getMessage()]);
        }
    }
}

public function indexId($IDCONTATO)
{
    try {
        $contato = Zwnclicontato::where('IDCONTATO', $IDCONTATO);

        if (!$contato) {
            $response = [
                'status' => 'error',
                'message' => 'Contato não encontrado',
                'data' => null,
            ];
            return response()->json($response, 404);
        }

        $response = [
            'status' => 'success',
            'message' => 'Contato recuperado com sucesso',
            'data' => $contato,
        ];
        return response()->json($response);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao recuperar o contato',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    }
}

public function indexClient($IDCLIENTE, Request $request) 
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
            $data = ['contatos' => $contatos, 'cliente' => $cliente];
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
    $userLogin = null;
    $empresaNome = null;
    $idempresa = null;
    $idusuario = null;

    if ($request->is('api/*')) {
        list($userName, $idusuario, $idempresa) = $this->getUserInfoFromJWT();
    } else {
        list($userName, $userLogin, $idusuario, $idempresa) = $this->getUserInfoFromSession();
    }

    try {
        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'TELEFONE' => 'nullable|string|max:15',
            'CELULAR' => 'nullable|string|max:15',
            'EMAIL' => 'nullable|string|max:60',
            'ATIVO' => 'required|boolean',
            'IDCLIENTE' => 'integer',
        ]);

        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECCREATEDBY'] = $userLogin;
        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECMODIFIEDBY'] = $userLogin;

        $validatedData['IDCLIENTE'] = $request->is('api/*') ? $validatedData['IDCLIENTE'] : $request->input('CLIENTE');

        if ($validatedData['TELEFONE'] == null && $validatedData['CELULAR'] == null && $validatedData['EMAIL'] == null) {
            throw new \Exception('Telefone OU Celular OU Email precisam ser cadastrados.');
        }

        $newContact = Zwnclicontato::create($validatedData);

        $logData = [
            'IDUSUARIO' => $idusuario,
            'CADASTRO' => 'Contato criado: ' . $validatedData['NOME'],
            'VALORANTERIOR' => null,
            'VALORNOVO' => json_encode($validatedData),
            'RECCREATEDBY' => $userLogin,
            'RECCREATEDON' => now(),
            'RECMODIFIEDBY' => $userLogin,
            'RECMODIFIEDON' => now(),
            'IDEMPRESA' => $idempresa,
        ];

        $this->createLog($logData, $request);

        if ($request->is('api/*')) {
            $response = [
                'status' => 'success',
                'message' => 'Contato criado com sucesso',
                'data' => $newContact,
            ];
            return response()->json($response, 201);
        } else {
            return redirect()->route('clientes.contatos', ['IDCLIENTE' => $newContact->IDCLIENTE])->with('success', 'Contato criado com sucesso.');
        }
    } catch (\Exception $e) {
        if ($request->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao criar o contato',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withInput()->withErrors(['error' => 'Erro ao criar o contato: ' . $e->getMessage()]);
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
    $userLogin = session('userLogin');
    $idusuario = session('IDUSUARIO');
    $idempresa = session('IDEMPRESA');

    return [$userName, $userLogin, $idusuario, $idempresa];
}

private function createLog($logData, $request) {
    $log = new Zwnlogcadastro();
    $log->fill($logData);
    $log->save();
}

private function createApiResponse($data, $statusCode) {
    $response = [
        'status' => 'success',
        'message' => 'Contato criado com sucesso!',
        'data' => $data,
    ];
    return response()->json($response, $statusCode);
}

private function createWebResponse($message) {
    return redirect()->route('clientes.contatos')->with('success', $message);
}

private function handleError($e, $request) {
    if ($request->is('api/*')) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao criar o contato',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    } else {
        return back()->withInput()->withErrors(['error' => 'Erro ao criar o contato: ' . $e->getMessage()]);
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
    
    public function edit($IDCLIENTE, $IDCONTATO)
    {
        $contato = Zwnclicontato::where('IDCLIENTE', $IDCLIENTE)
        ->where('IDCONTATO', $IDCONTATO)
        ->first();

        if (!$contato) {
            if (request()->is('api/*')) {
                return response()->json(['error' => 'Contato não encontrado'], 404);
            } else {
                abort(404);
            }
        }

        $cliente = Zwncliente::where('IDCLIENTE', $IDCLIENTE);

        if (request()->is('api/*')) {
            return response()->json(['contato' => $contato, 'cliente' => $cliente]);
        } else {
            return view('editContact', compact('contato', 'cliente'));
        }
    }

    
    public function update(Request $request, $IDCLIENTE, $IDCONTATO)
    {
        try {
            $validatedData = $request->validate([
                'NOME' => 'required|string|max:255',
                'APELIDO' => 'required|string|max:255',
                'TELEFONE' => 'nullable|string|max:15',
                'CELULAR' => 'nullable|string|max:15',
                'EMAIL' => 'nullable|string|max:60',
                'ATIVO' => 'required|boolean',
                'CLIENTE' => 'exists:zwnclientes,IDCLIENTE',
            ]);
    
            if ($validatedData['TELEFONE'] == null && $validatedData['CELULAR'] == null && $validatedData['EMAIL'] == null) {
                throw new \Exception('Telefone OU Celular OU Email precisam ser cadastrados.');
            }
    
            $contato = Zwnclicontato::where('IDCLIENTE', $IDCLIENTE)
            ->where('IDCONTATO', $IDCONTATO)
            ->first();

            if (!$contato) {
                if ($request->is('api/*')) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Contato não encontrado',
                        'data' => null,
                    ];
                    return response()->json($response, 404);
                } else {
                    abort(404);
                }
            }
    
            list($userName, $userLogin, $idusuario, $idempresa) = $this->getUserInfoForUpdate($request);
    
            $validatedData['RECMODIFIEDON'] = now();
            $validatedData['RECMODIFIEDBY'] = $request->is('api/*') ? $userName : $userLogin;
            $contatoAntesDaAtualizacao = $contato->toArray();
            $contato->update($validatedData);
            $contatoDepoisDaAtualizacao = $contato->toArray();
    
            $logData = [
                'IDUSUARIO' => $idusuario,
                'CADASTRO' => 'Contato atualizado: ' . $contato->NOME,
                'VALORANTERIOR' => json_encode($contatoAntesDaAtualizacao),
                'VALORNOVO' => json_encode($contatoDepoisDaAtualizacao),
                'RECMODIFIEDBY' => $request->is('api/*') ? $userName : $userLogin,
                'RECMODIFIEDON' => now(),
                'IDEMPRESA' => $idempresa,
            ];
    
            $this->createLog($logData, $request);
    
            if ($request->is('api/*')) {
                $response = [
                    'status' => 'success',
                    'message' => 'Contato atualizado com sucesso',
                    'data' => $contato,
                ];
                return response()->json($response);
            } else {
                return redirect()->route('clientes.contatos', ['IDCLIENTE' => $contato->IDCLIENTE])->with('success', 'Contato atualizado com sucesso');
            }
        } catch (\Exception $e) {
            if ($request->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Erro ao atualizar o contato',
                    'data' => $e->getMessage(),
                ];
                return response()->json($response, 400);
            } else {
                return back()->withErrors(['error' => 'Erro ao atualizar o contato: ' . $e->getMessage()]);
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
    
    public function delete(Request $request, $IDCLIENTE, $IDCONTATO)
    {
        try {
            $contato = Zwnclicontato::where('IDCLIENTE', $IDCLIENTE)
            ->where('IDCONTATO', $IDCONTATO)
            ->first();

            if (!$contato) {
                if ($request->is('api/*')) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Contato não encontrado',
                        'data' => null,
                    ];
                    return response()->json($response, 404);
                } else {
                    abort(404);
                }
            }
            
            $contato::where('IDCLIENTE', $IDCLIENTE)
            ->where('IDCONTATO', $IDCONTATO)
            ->delete();
            

            if ($request->is('api/*')) {
                $response = [
                    'status' => 'success',
                    'message' => 'Contato excluído com sucesso',
                    'data' => null, 
                ];
                return response()->json($response);
            } else {
                return redirect()->route('clientes.contatos', ['IDCLIENTE' => $contato->IDCLIENTE])->with('success', 'Contato excluído com sucesso');
            }
        } catch (\Exception $e) {
            if ($request->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Erro ao excluir o contato',
                    'data' => $e->getMessage(),
                ];
                return response()->json($response, 400);
            } else {
                return back()->withErrors(['error' => 'Erro ao excluir o contato: ' . $e->getMessage()]);
            }
        }
    }
}
