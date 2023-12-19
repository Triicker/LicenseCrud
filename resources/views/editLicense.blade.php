<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Licença</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="container">

    <form method="POST" action="{{ route('licencas.update',['IDCOLIGADA' => $licenca->IDCOLIGADA, 'IDCLIENTE' =>  $licenca->IDCLIENTE, 'IDPRODUTO' =>  $licenca->IDPRODUTO]) }}">
        @csrf
        @method('PUT')
    
        <div class="mb-3">
            <label for="DTFIM" class="form-label">Data de Fim</label>
            <input type="DATE" class="form-control" id="DTFIM" name="DTFIM" value="{{ \Carbon\Carbon::parse($licenca->DTFIM)->format('Y-m-d') }}">
        </div>
        <div class="mb-3">
    <label for="ATIVO" class="form-label">Ativo</label>
    <select class="form-select" id="ATIVO" name="ATIVO">
        <option value="1" {{ $licenca->ATIVO == 1 ? 'selected' : '' }}>Sim</option>
        <option value="0" {{ $licenca->ATIVO == 0 ? 'selected' : '' }}>Não</option>
    </select>
</div>

        <div class="text-center">
        <button type="submit" class="btn-s btn-suc">Atualizar</button>
        <button type="button" class="btn-ajust btn-edi" data-bs-dismiss="modal">Cancelar</button>
    </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+oL6F5f5f5k5F5eLl5d5F5t5f5R5O5y5.5G5v5Q5" crossorigin="anonymous"></script>
</body>
</html>
