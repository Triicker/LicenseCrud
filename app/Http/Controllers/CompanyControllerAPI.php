<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogCadastroController;
use App\Models\Zwnlogcadastro;
use App\Models\Zwnusuempresa;
use App\Models\Zwnempresa;
use Illuminate\Http\Request;
use JWTAuth;

class CompanyControllerAPI extends Controller
{
    public function index()
    {
        try {
            $empresas = Zwnempresa::all();

            $response = [
                'status' => 'success',
                'message' => 'Empresas recuperadas com sucesso',
                'data' => $empresas,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar empresas',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }

    public function indexId($IDEMPRESA)
    {
        try {
            $empresa = Zwnempresa::find($IDEMPRESA);

            if (!$empresa) {
                $response = [
                    'status' => 'error',
                    'message' => 'Empresa não encontrada',
                    'data' => null,
                ];
                return response()->json($response, 404);
            }

            $response = [
                'status' => 'success',
                'message' => 'Empresa recuperada com sucesso',
                'data' => $empresa,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar a empresa',
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
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $userName = $user->USUARIO;

        $validatedData = $request->validate([
            'NOME' => 'required|string|max:255',
            'APELIDO' => 'required|string|max:255',
            'ATIVO' => 'required|boolean',
        ]);

        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECCREATEDBY'] = $userName;

        $empresa = Zwnempresa::create($validatedData);

        $logData = [
            'IDUSUARIO' => $user->IDUSUARIO,
            'CADASTRO' => 'Empresa criada: ' . $empresa->NOME,
            'VALORANTERIOR' => null,
            'VALORNOVO' => json_encode($validatedData),
            'RECCREATEDBY' => $userName,
            'RECCREATEDON' => now(),
            'RECMODIFIEDBY' => $userName,
            'RECMODIFIEDON' => now(),
        ];

        $logData['IDEMPRESA'] = $this->getEmpresaID($user);

        $log = new Zwnlogcadastro();
        $log->fill($logData);
        $log->save();

        $response = [
            'status' => 'success',
            'message' => 'Empresa cadastrada com sucesso',
            'data' => $empresa,
        ];

        return response()->json($response, 201);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao criar a empresa',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    }
}


public function update(Request $request, $IDEMPRESA)
{
    try {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $userName = $user->USUARIO;

        $validatedData = $request->validate([
            'NOME' => 'string|max:255',
            'APELIDO' => 'string|max:255',
            'ATIVO' => 'boolean',
        ]);

        $empresa = Zwnempresa::find($IDEMPRESA);

        if (!$empresa) {
            $response = [
                'status' => 'error',
                'message' => 'Empresa não encontrada',
                'data' => null,
            ];
            return response()->json($response, 404);
        }

        $logData = [
            'IDUSUARIO' => $user->IDUSUARIO,
            'CADASTRO' => 'Empresa alterada: ' . $empresa->NOME,
            'VALORANTERIOR' => json_encode($empresa->getAttributes()), // Registro anterior
            'VALORNOVO' => json_encode($validatedData), // Registro atualizado
            'RECCREATEDBY' => $userName,
            'RECCREATEDON' => now(),
        ];

        $logData['IDEMPRESA'] = $this->getEmpresaID($user);

        $log = new Zwnlogcadastro();
        $log->fill($logData);
        $log->save();

        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECMODIFIEDBY'] = $userName;

        $empresa->update($validatedData);

        $response = [
            'status' => 'success',
            'message' => 'Empresa alterada com sucesso',
            'data' => $empresa,
        ];

        return response()->json($response);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao atualizar a empresa',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    }
}

    public function delete($IDEMPRESA)
    {
        try {
            $empresa = Zwnempresa::find($IDEMPRESA);

            if (!$empresa) {
                $response = [
                    'status' => 'error',
                    'message' => 'Empresa não encontrada',
                    'data' => null,
                ];
                return response()->json($response, 404);
            }

            $empresas = Zwnusuempresa::where('IDEMPRESA', $empresa->IDEMPRESA)->count();

    if ($empresas > 0) {
        if (request()->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Não é possível excluir a Empresa. Existem usuários associados.',
                'data' => null,
            ];
            return response()->json($response, 400);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não é possível excluir a Empresa. Existem usuários associados.',
                'data' => null,
            ];
            return response()->json($response, 400);
        }
    }

            $empresa->delete();

            $response = [
                'status' => 'success',
                'message' => 'Empresa excluída com sucesso',
                'data' => null,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao excluir a empresa',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }
}
