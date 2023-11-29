<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LogCadastroController;
use App\Models\Zwnusuario;
use App\Models\Zwnempresa;
use App\Models\Zwnusuempresa;
use App\Models\Zwnlogcadastro;

use Illuminate\Http\Request;
use JWTAuth;

class UserControllerAPI extends Controller
{

    public function index()
    {
        try {
            $usuarios = Zwnusuario::all();

            $response = [
                'status' => 'success',
                'message' => 'Usuários recuperados com sucesso',
                'data' => $usuarios,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar usuários',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }

    public function indexId($IDUSUARIO)
    {
        try {
            $usuario = Zwnusuario::find($IDUSUARIO);

            if (!$usuario) {
                $response = [
                    'status' => 'error',
                    'message' => 'Usuário não encontrado',
                    'data' => null,
                ];
                return response()->json($response, 404);
            }

            $response = [
                'status' => 'success',
                'message' => 'Usuário recuperado com sucesso',
                'data' => $usuario,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao recuperar o usuário',
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
            'USUARIO' => 'required|string|max:255|unique:zwnusuarios',
            'SENHA' => 'required|string|min:6',
            'EMAIL' => 'required|email|unique:zwnusuarios',
            'ATIVO' => 'required|boolean',
        ]);

        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECCREATEDBY'] = $userName;
        $validatedData['RECMODIFIEDBY'] = $userName;

        $usuario = Zwnusuario::create($validatedData);

        $logData = [
            'IDUSUARIO' => $user->IDUSUARIO,
            'CADASTRO' => 'Usuário cadastrado: ' . $usuario->USUARIO,
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
            'message' => 'Usuário cadastrado com sucesso',
            'data' => $usuario,
        ];

        return response()->json($response, 201);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao criar o usuário',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    }
}

    


public function update(Request $request, $IDUSUARIO)
{
    try {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $userName = $user->USUARIO;

        $validatedData = $request->validate([
            'NOME' => 'string|max:255',
            'APELIDO' => 'string|max:255',
            'USUARIO' => 'string|max:255',
            'SENHA' => 'string|min:6', 
            'ATIVO' => 'boolean',
        ]);

        $usuario = Zwnusuario::find($IDUSUARIO);

        if (!$usuario) {
            $response = [
                'status' => 'error',
                'message' => 'Usuário não encontrado',
                'data' => null,
            ];
            return response()->json($response, 404);
        }

        $dadosAntigos = $usuario->toArray();

        if (isset($validatedData['SENHA'])) {
            $validatedData['SENHA'] = bcrypt($validatedData['SENHA']);
        }

        $usuario->update($validatedData);

        $dadosNovos = $usuario->toArray();

        if ($dadosAntigos !== $dadosNovos) {
            $logData = [
                'IDUSUARIO' => $user->IDUSUARIO,
                'CADASTRO' => 'Usuário atualizado: ' . $usuario->USUARIO,
                'VALORANTERIOR' => json_encode($dadosAntigos),
                'VALORNOVO' => json_encode($dadosNovos),
                'RECMODIFIEDBY' => $userName,
                'RECMODIFIEDON' => now(),
            ];

            $logData['IDEMPRESA'] = $this->getEmpresaID($user);

            $log = new Zwnlogcadastro();
            $log->fill($logData);
            $log->save();
        }

        $response = [
            'status' => 'success',
            'message' => 'Usuário atualizado com sucesso',
            'data' => $usuario,
        ];

        return response()->json($response);
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao atualizar o usuário',
            'data' => $e->getMessage(),
        ];
        return response()->json($response, 400);
    }
}



    public function delete($IDUSUARIO)
    {
        try {
            $usuario = Zwnusuario::find($IDUSUARIO);

            if (!$usuario) {
                $response = [
                    'status' => 'error',
                    'message' => 'Usuário não encontrado',
                    'data' => null,
                ];
                return response()->json($response, 404);
            }

            $usuarios = Zwnusuempresa::where('IDUSUARIO', $usuario->IDUSUARIO)->count();

    if ($usuarios > 0) {
        if (request()->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Não é possível excluir o Usuário. Existem empresas associadas.',
                'data' => null,
            ];
            return response()->json($response, 400);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não é possível excluir o Usuário. Existem empresas associadas.',
                'data' => null,
            ];
            return response()->json($response, 400);
        }
    }

            $usuario->delete();

            $response = [
                'status' => 'success',
                'message' => 'Usuário excluído com sucesso',
                'data' => null,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao excluir o usuário',
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }
}
