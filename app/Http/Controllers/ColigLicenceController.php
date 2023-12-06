<?php
namespace App\Http\Controllers;

use App\Http\Controllers\LogCadastroController;
use App\Models\Zwnlogcadastro;
use App\Models\Zwncoliglicenca;
use App\Models\Zwncoligada;
use App\Models\Zwnproduto;
use App\Models\Zwncliente;
use App\Models\Zwnusuempresa;
use App\Models\Zwnempresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class ColigLicenceController extends Controller
{
    public function index(Request $request, $IDCOLIGADA = null, $IDPRODUTO = null, $IDCLIENTE = null)
    {
        $query = Zwncoliglicenca::with(['coligada', 'produto', 'cliente']);
        $produtos = Zwnproduto::all();

        if ($IDCOLIGADA !== null) {
            $query->where('IDCOLIGADA', $IDCOLIGADA);
        }
    
        if ($IDPRODUTO !== null) {
            $query->where('IDPRODUTO', $IDPRODUTO);
        }
    
        if ($IDCLIENTE !== null) {
            $query->where('IDCLIENTE', $IDCLIENTE);
        }
        $licencas = $query->get();
        $cliente = $licencas->isNotEmpty() ? $licencas->first()->cliente : null;
        $produto = $licencas->isNotEmpty() ? $licencas->first()->produto : null;
        $coligada = $licencas->isNotEmpty() ? $licencas->first()->coligada : null;
        
        if ($licencas->isEmpty()) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json(['message' => 'Nenhum resultado encontrado'], 404);
            } else {
                return view('indexLicense', compact('licencas', 'cliente', 'produto', 'coligada', 'produtos'))->with('message', 'Nenhum resultado encontrado');
            }
        }
    
        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json(['licencas' => $licencas]);
        } else {
            return view('indexLicense', compact('licencas', 'cliente', 'produto', 'coligada', 'produtos'));
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
    $empresaID = null;
    $user = null;

    if ($request->is('api/*')) {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        if ($user) {
            $userName = $user->USUARIO;
            $empresaID = $this->getEmpresaID($user);
        }
    } else {
        $userName = session('userName');
        $empresaID = session('IDEMPRESA');
        $user = $this->getUserInfoFromSession();
    }

    try {
        $validatedData = $request->validate([
            'IDFILIAL' => 'integer',
            'DTINICIO' => 'date',
            'DTFIM' => 'date',
            'ATIVO' => 'boolean',
            'IDPRODUTO' => 'integer',
        ]);

        $validatedData['IDFILIAL'] = 1;
        $validatedData['IDCLIENTE'] = $request->input('IDCLIENTE');
        $validatedData['IDPRODUTO'] = $request->input('IDPRODUTO');
        $validatedData['IDCOLIGADA'] = $request->input('IDCOLIGADA');
        $validatedData['RECCREATEDON'] = now();
        $validatedData['RECCREATEDBY'] = $userName;
        $validatedData['RECMODIFIEDON'] = now();
        $validatedData['RECMODIFIEDBY'] = $userName;

        $dtInicio = $request->input('DTINICIO');
        $dtFim = $request->input('DTFIM');

        if ($dtInicio > $dtFim) {
        throw new \Exception('A data de início não pode ser maior que a data de fim.');
        }

        $coligadaLicenca = Zwncoliglicenca::create($validatedData);

        $logData = [
            'IDUSUARIO' => $user->IDUSUARIO,
            'CADASTRO' => 'Licença da coligada criada',
            'VALORANTERIOR' => null,
            'VALORNOVO' => json_encode($validatedData),
            'RECCREATEDBY' => $userName,
            'RECCREATEDON' => now(),
            'RECMODIFIEDBY' => $userName,
            'RECMODIFIEDON' => now(),
        ];

        $logData['IDEMPRESA'] = $empresaID;

        $this->createLog($logData);

        if ($request->is('api/*')) {
            return $this->createApiResponse('Licença da coligada criada com sucesso', $coligadaLicenca, 201);
        } else {
            return $this->createWebResponse('Licença da coligada criada com sucesso!');
        }
    } catch (\Exception $e) {
        return $this->handleError($e, $request);
    }
}

private function getUserInfoFromSession() {
    $userName = session('userName');
    $user = new \stdClass();
    $user->IDUSUARIO = session('IDUSUARIO');
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
    return redirect()->route('coligada.index')->with('success', $message);
}

private function handleError($e, $request) {
    if ($request->is('api/*')) {
        return $this->createApiResponse('Erro ao criar a licença da coligada', $e->getMessage(), 400);
    } else {
        return back()->withInput()->withErrors(['error' => 'Erro ao criar a licença da coligada: ' . $e->getMessage()]);
    }
}

    public function create()
    {
        $clientes = Zwncliente::all();
        $coligadas = Zwncoligada::all();
        $produtos = Zwnproduto::all();

        if (request()->is('api/*')) {
            return response()->json(['clientes' => $clientes, 'coligadas' => $coligadas, 'produtos' => $produtos]);
        } else {
            return view('createColiglicenca', compact('clientes', 'coligadas', 'produtos'));
        }
    }

    public function edit($IDCOLIGADA)
{
    $licenca = Zwncoliglicenca::find($IDCOLIGADA);

    if (!$licenca) {
        if (request()->is('api/*')) {
            return response()->json(['error' => 'Licença da coligada não encontrada'], 404);
        } else {
            abort(404);
        }
    }

    $clientes = Zwncliente::all();

    if (request()->is('api/*')) {
        return response()->json(['licenca' => $licenca, 'clientes' => $clientes]);
    } else {
        return view('editLicence', compact('licenca', 'clientes'));
    }
}


public function update(Request $request, $IDCOLIGADA)
{
    $validatedData = $request->validate([
        'DTFIM' => 'date',
        'ATIVO' => 'boolean',
    ]);

    $coligada = Zwncoliglicenca::find($IDCOLIGADA);

    if (!$coligada) {
        if ($request->is('api/*')) {
            return response()->json(['error' => 'Licença da coligada não encontrada'], 404);
        } else {
            abort(404);
        }
    }

    $userName = null;
    $empresaID = null;
    $user = null;

    if ($request->is('api/*')) {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        if ($user) {
            $userName = $user->USUARIO;
            $empresaID = $this->getEmpresaID($user);
        }
    } else {
        $userName = session('userName');
        $empresaID = session('IDEMPRESA');
        $user = $this->getUserInfoFromSession();
    }
    $validatedData['ATIVO'] = $request->input('ATIVO') == 1;
    $validatedData['RECMODIFIEDON'] = now();
    $validatedData['RECMODIFIEDBY'] = $userName;

    $coligada->update($validatedData);

    $logData = [
        'IDUSUARIO' => $user->IDUSUARIO,
        'CADASTRO' => 'Licença da coligada atualizada',
        'VALORANTERIOR' => json_encode($coligada->getOriginal()),
        'VALORNOVO' => json_encode($validatedData),
        'RECCREATEDBY' => $userName,
        'RECCREATEDON' => now(),
    ];

    $logData['IDEMPRESA'] = $empresaID;

    $this->createLog($logData);

    if ($request->is('api/*')) {
        return response()->json(['message' => 'Licença da coligada atualizada com sucesso']);
    } else {
        return redirect()->route('licencas.coligada', ['IDCOLIGADA' => $IDCOLIGADA])->with('success', 'Licença da coligada atualizada com sucesso');
    }
}


public function delete(Request $request, $IDCOLIGADA)
{
    $coligada = Zwncoliglicenca::find($IDCOLIGADA);

    if (!$coligada) {
        if ($request->is('api/*')) {
            return response()->json(['error' => 'Licença da coligada não encontrada'], 404);
        } else {
            abort(404);
        }
    }

    $coligadas = Zwncoliglicenca::where('IDCOLIGADA', $coligada->IDCOLIGADA)->count();

    if ($coligadas > 0) {
        if (request()->is('api/*')) {
            $response = [
                'status' => 'error',
                'message' => 'Não é possível excluir a Coligada. Existem Licenças associadas.',
                'data' => null,
            ];
            return response()->json($response, 400);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não é possível excluir a Coligada. Existem Licenças associadas.',
                'data' => null,
            ];
            return response()->json($response, 400);
        }
    }

    $coligada->delete();

    if ($request->is('api/*')) {
        return response()->json(['message' => 'Licença da coligada excluída com sucesso']);
    } else {
        return redirect()->route('coligadas.index')->with('success', 'Coligada excluída com sucesso');
    }
}

}
 

