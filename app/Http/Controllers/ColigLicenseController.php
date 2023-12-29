<?php
namespace App\Http\Controllers;
use App\Models\Zwnlogcadastro;
use App\Models\Zwncoliglicenca;
use App\Models\Zwncoligada;
use App\Models\Zwnproduto;
use App\Models\Zwncliente;
use App\Models\Zwnusuempresa;
use Illuminate\Http\Request;
use JWTAuth;

class ColigLicenseController extends Controller
{
    public function index(Request $request, $IDCOLIGADA = null,  $IDCLIENTE = null, $IDPRODUTO = null)
    {
        $query = Zwncoliglicenca::with(['coligada','cliente', 'produto']);

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
        $clientes = Zwncliente::where('IDCLIENTE', $IDCLIENTE);
        $coligadas = Zwncoligada::where('IDCLIENTE', $IDCLIENTE)->where('IDCOLIGADA', $IDCOLIGADA);

        if ($licencas->isEmpty()) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json(['message' => 'Nenhum resultado encontrado'], 404);
            } else {
                return view('indexLicense', compact('licencas', 'cliente', 'produto', 'coligada', 'produtos', 'clientes', 'coligadas'));
            }
        }
    
        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json(['licencas' => $licencas]);
        } else {
            return view('indexLicense', compact('licencas', 'cliente', 'produto', 'coligada', 'produtos', 'clientes', 'coligadas'));
        }
    }

    public function indexClient($IDCLIENTE, $IDCOLIGADA, Request $request)
    {
        try {
            $licencas = Zwncoliglicenca::where('IDCLIENTE', $IDCLIENTE)->where('IDCOLIGADA', $IDCOLIGADA)->get();
            $coligada = Zwncoligada::where('IDCLIENTE', $IDCLIENTE)->where('IDCOLIGADA', $IDCOLIGADA)->first();
            $clientes = Zwncliente::all();
            $cliente = $clientes->find($IDCLIENTE);
            $produtos = Zwnproduto::all();

            if ($request->is('api/*') || $request->wantsJson()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Coligadas do cliente recuperadas com sucesso',
                    'data' => $licencas,
                ];
                return response()->json($response);
            } else {
                $data = ['licencas' => $licencas, 'coligada' => $coligada, 'cliente' => $cliente, 'produtos' => $produtos];
                return view('indexLicense', compact('data','licencas','cliente','coligada'));
            }
        } catch (\Exception $e) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $response = [
                    'status' => 'error',
                    'message' => 'Erro ao recuperar licenças da coligada do cliente',
                    'data' => $e->getMessage(),
                ];
                return response()->json($response, 400);
            } else {
                return back()->withErrors(['error' => 'Erro ao recuperar licenças da coligada do cliente: ' . $e->getMessage()]);
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
                'IDPRODUTO' => 'integer',
                'IDCLIENTE' => 'integer',
                'IDCOLIGADA' => 'integer',
                'DTINICIO' => 'required|date',
                'DTFIM' => 'nullable|date',
                'ATIVO' => 'boolean',
            ]);
            $validatedData['IDCLIENTE'] = $request->input('IDCLIENTE');
            $validatedData['IDCOLIGADA'] = $request->input('IDCOLIGADA');
            $validatedData['RECCREATEDON'] = now();
            $validatedData['RECCREATEDBY'] = $userName;
            $validatedData['RECMODIFIEDON'] = now();
            $validatedData['RECMODIFIEDBY'] = $userName;

            if ($validatedData['DTFIM'] != null) {
                $dtInicio = $validatedData['DTINICIO'];
                $dtFim = $validatedData['DTFIM'];
                if ($dtInicio > $dtFim) {
                    throw new \Exception('A data de início não pode ser maior que a data de fim.');
                }
                $validatedData['DTFIM'] = $validatedData['DTFIM'] ?? null;
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
                return redirect()->route('coligadas.licencas', ['IDCLIENTE' => $validatedData['IDCLIENTE'], 'IDCOLIGADA' => $validatedData['IDCOLIGADA']])->with('success', 'Coligada criada com sucesso.');            
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
        return redirect()->route('licencas.cliente')->with('success', $message);
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

    public function edit($IDCLIENTE,$IDCOLIGADA,$IDPRODUTO)
    {
        $licenca = Zwncoliglicenca::where('IDCLIENTE', $IDCLIENTE)
        ->where('IDCOLIGADA', $IDCOLIGADA)
        ->where('IDPRODUTO', $IDPRODUTO)
        ->first();
        
        if (!$licenca) {
            if (request()->is('api/*')) {
                return response()->json(['error' => 'Licença da coligada não encontrada'], 403);
            } else {
                abort(403);
            }
        }
        $clientes = Zwncliente::where('IDCLIENTE', $IDCLIENTE)->get();
       
        if (request()->is('api/*')) {
            return response()->json(['licenca' => $licenca, 'clientes' => $clientes]);
        } else {
            return view('editLicense', compact('licenca', 'clientes'));
        }
    }

    public function update(Request $request, $IDCLIENTE, $IDCOLIGADA, $IDPRODUTO)
    {
        $validatedData = $request->validate([
            'DTFIM' => 'nullable|date',
            'ATIVO' => 'boolean',
        ]);

        $licenca = Zwncoliglicenca::where('IDCLIENTE', $IDCLIENTE)
        ->where('IDCOLIGADA', $IDCOLIGADA)
        ->where('IDPRODUTO', $IDPRODUTO)
        ->first();

        if (!$licenca) {
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

        $licenca::where('IDCOLIGADA', $IDCOLIGADA)
            ->where('IDCLIENTE', $IDCLIENTE)
            ->where('IDPRODUTO', $IDPRODUTO)->update($validatedData);

        $logData = [
            'IDUSUARIO' => $user->IDUSUARIO,
            'CADASTRO' => 'Licença da coligada atualizada',
            'VALORANTERIOR' => json_encode($licenca->getOriginal()),
            'VALORNOVO' => json_encode($validatedData),
            'RECCREATEDBY' => $userName,
            'RECCREATEDON' => now(),
        ];

        if ($request->has('DTINICIO')) {
            $dtInicio = $request->input('DTINICIO');
            $dtFim = $validatedData['DTFIM'];
    
            if ($dtFim != null && $dtInicio > $dtFim) {
                throw new \Exception('A data de início não pode ser maior que a data de fim.');
            }
        }

        $logData['IDEMPRESA'] = $empresaID;

        $this->createLog($logData);

        if ($request->is('api/*')) {
            return response()->json(['message' => 'Licença da coligada atualizada com sucesso']);
        } else {
            return redirect()->route('coligadas.licencas', ['IDCLIENTE' => $IDCLIENTE, 'IDCOLIGADA' => $IDCOLIGADA])->with('success', 'Licença da coligada atualizada com sucesso');
        }
    }

    public function delete(Request $request, $IDCLIENTE, $IDCOLIGADA, $IDPRODUTO)
    {
        $coligada = Zwncoliglicenca::where('IDCLIENTE', $IDCLIENTE)
        ->where('IDCOLIGADA', $IDCOLIGADA)
        ->where('IDPRODUTO', $IDPRODUTO)
        ->first();

        if (!$coligada) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'Licença da coligada não encontrada'], 404);
            } else {
                abort(404);
            }
        }

        $coligada::where('IDCLIENTE', $IDCLIENTE)
        ->where('IDCOLIGADA', $IDCOLIGADA)
        ->where('IDPRODUTO', $IDPRODUTO)->delete();

        if ($request->is('api/*')) {
            return response()->json(['message' => 'Licença da coligada excluída com sucesso']);
        } else {
            return redirect()->route('coligadas.licencas', ['IDCLIENTE' => $IDCLIENTE, 'IDCOLIGADA' => $IDCOLIGADA])->with('success', 'Licença da Coligada excluída com sucesso');
        }
    }
}
 

