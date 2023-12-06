<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LogCadastroController;
use App\Http\Controllers\CompanyControllerAPI;
use App\Models\Zwncoliglicenca;
use App\Models\Zwnusuempresa;
use App\Models\Zwnempresa;
use App\Models\Zwnlogcadastro;
use App\Models\Zwnproduto;

use Illuminate\Support\Facades\Auth;
use JWTAuth;

use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request)
{
    try {
        $userInfo = $this->getUserInfoFromSession();

        if (!$userInfo->idempresa) {
            $errorMessage = 'ID da empresa não encontrado na sessão.';
            if ($request->is('api/*') || $request->wantsJson()) {
                $response = [
                    'status' => 'error',
                    'message' => $errorMessage,
                    'data' => null,
                ];
                return response()->json($response, 400);
            } else {
                return back()->withErrors(['error' => $errorMessage]);
            }
        }

        $produtos = Zwnproduto::where('idempresa', $userInfo->idempresa)->get();

        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'success',
                'message' => 'Produtos recuperados com sucesso',
                'data' => $produtos,
            ];
            return response()->json($response);
        } else {
            return view('indexProduct', compact('produtos'));
        }
    } catch (\Exception $e) {
        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar produtos',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao recuperar produtos: ' . $e->getMessage()]);
        }
    }
}

    


public function indexId($IDPRODUTO)
{
    try {
        $produto = Zwnproduto::find($IDPRODUTO);

        if (!$produto) {
            $response = [
                'status' => 'error',
                'message' => 'Produto não encontrado',
                'data' => null,
            ];
            return response()->json($response, 404);
        }

        $response = [
            'status' => 'success',
            'message' => 'Produto recuperado com sucesso',
            'data' => $produto,
        ];
        return response()->json($response);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao recuperar o produto',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
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
        $user = null;

        if ($request->is('api/*')) {
            $user = $this->getUserInfoFromJWT();
        } else {
            $user = $this->getUserInfoFromSession();
        }

        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'ATIVO' => 'required|boolean',
        ]);

        $existingProduct = Zwnproduto::where('NOME', $validatedData['NOME'])
            ->where('IDEMPRESA', $user->idempresa)
            ->exists();

        if ($existingProduct) {
            return back()->withErrors(['error' => 'Já existe um produto com esse nome']);
        }

        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECCREATEDBY'] = $user->userLogin;
        $validatedData['IDEMPRESA'] = $user->idempresa;
        
        $produto = Zwnproduto::create($validatedData);

        $logData = [
            'IDUSUARIO' => $user->idusuario,
            'CADASTRO' => 'Produto criado: ' . $produto->NOME,
            'VALORANTERIOR' => null,
            'VALORNOVO' => json_encode($validatedData),
            'RECCREATEDBY' => $user->userLogin,
            'RECCREATEDON' => now(),
            'RECMODIFIEDBY' => $user->userLogin,
            'RECMODIFIEDON' => now(),
            'IDEMPRESA' => $user->idempresa,
        ];

        $this->createLog($logData);

        if ($request->is('api/*')) {
            return response()->json(['message' => 'Produto criado com sucesso', 'data' => $produto], 201);
        } else {
            return response()->json(['message' => 'Produto criado com sucesso!']);
        }
    } catch (\Exception $e) {
        return $this->handleError($e, $request);
    }
}




private function getUserInfoFromSession()
{
    $user = new \stdClass();
    $user->userName = session('userName');
    $user->userLogin = session('userLogin');
    $user->idusuario = session('IDUSUARIO');
    $user->idempresa = session('IDEMPRESA');

    return $user;
}


private function createLog($logData) {
    $log = new Zwnlogcadastro();
    $log->fill($logData);
    $log->save();
}

private function createApiResponse($message, $data = null, $statusCode = 200) {
    $response = [
        'status' => 'success',
        'message' => $message,
    ];

    if (!is_null($data)) {
        $response['data'] = $data;
    }

    return response()->json($response, $statusCode);
}

private function createWebResponse($message) {
    return redirect()->route('produtos.index')->with('success', $message);
}

private function handleError($e, $request) {
    if ($request->is('api/*')) {
        return $this->createApiResponse('Erro ao criar o produto', $e->getMessage(), 400);
    } else {
        return back()->withInput()->withErrors(['error' => 'Erro ao criar o produto: ' . $e->getMessage()]);
    }
}


public function edit($IDPRODUTO)
{
    $produto = Zwnproduto::find($IDPRODUTO);

    if (!$produto) {
        if (request()->is('api/*')) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        } else {
            abort(404);
        }
    }

    if (request()->is('api/*')) {
        return response()->json(['produto' => $produto]);
    } else {
        return view('editProduct', compact('produto'));
    }
}


public function update(Request $request, $IDPRODUTO)
{
    try {

        $produto = Zwnproduto::find($IDPRODUTO);

        if (!$produto) {
            if ($request->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Produto não encontrado',
                    'data' => null,
                ];
                return response()->json($response, 404);
            } else {
                abort(404);
            }
        }

        $dtfimMenorQueHoje = Zwncoliglicenca::where('IDPRODUTO', $produto->IDPRODUTO)
            ->whereDate('DTFIM', '<', now())
            ->exists();

            if (!$dtfimMenorQueHoje) {
                if ($request->is('api/*')) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Não é possível alterar o campo ATIVO. Pois há licença vigente.',
                        'data' => null,
                    ];
                    return response()->json($response, 400);
                } else {
                    return back()->withErrors(['error' => 'Não é possível alterar o campo ATIVO. Pois há licença vigente']);
                }
            }
        $validatedData = $request->validate([
            'NOME' => 'string|max:255',
            'APELIDO' => 'string|max:255',
            'ATIVO' => 'boolean',
        ]);
        
        $userLogin = null;

        if ($request->is('api/*')) {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);

            if ($user) {
                $userLogin = $user->USUARIO;
            }
        } else {
            $userLogin = session('userLogin');
        }

        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECMODIFIEDBY'] = $userLogin;

        $produto->update($validatedData);

        if ($request->is('api/*')) {
            $response = [
                'status' => 'success',
                'message' => 'Produto atualizado com sucesso',
                'data' => $produto,
            ];
            return response()->json($response);
        } else {
            return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso');
        }
    } catch (\Exception $e) {
        if ($request->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao atualizar o produto',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao atualizar o produto: ' . $e->getMessage()]);
        }
    }
}


public function delete($IDPRODUTO)
{
    try {
        $produto = Zwnproduto::find($IDPRODUTO);

        if (!$produto) {
            if (request()->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Produto não encontrado',
                    'data' => null,
                ];
                return response()->json($response, 404);
            } else {
                abort(404);
            }
        }

        $licença = Zwncoliglicenca::where('IDPRODUTO', $produto->IDPRODUTO)->count();

        if ($licença > 0) {
            if (request()->is('api/*')) {
                $response = [
                    'status' => 'error',
                    'message' => 'Não é possível excluir o Produto. Existem licenças associadas.',
                    'data' => null,
                ];
                return response()->json($response, 400);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Não é possível excluir o Produto. Existem licenças associadas.',
                    'data' => null,
                ];
                return response()->json($response, 400);
            }
        }

        $produto->delete();

        if (request()->is('api/*')) {
            $response = [
                'status' => 'success',
                'message' => 'Produto excluído com sucesso',
                'data' => null, 
            ];
            return response()->json($response);
        } else {
            return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso');
        }
    } catch (\Exception $e) {
        if (request()->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao excluir o produto',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        } else {
            return back()->withErrors(['error' => 'Erro ao excluir o produto: ' . $e->getMessage()]);
        }
    }
}

    

}
