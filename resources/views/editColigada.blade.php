<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Coligada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="container">

    <form method="POST" action="{{ route('clientes.coligadas.update', ['IDCLIENTE' => $coligada->IDCLIENTE, 'IDCOLIGADA' => $coligada->IDCOLIGADA ]) }}">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="IDCOLIGADA" class="form-label">ID</label>
        <input type="text" class="form-control" id="IDCOLIGADA" name="IDCOLIGADA" value="{{ $coligada->IDCOLIGADA }}">
    </div>
    <div class="mb-3">
        <label for="NOME" class="form-label">Nome</label>
        <input type="text" class="form-control" id="NOME" name="NOME" value="{{ $coligada->NOME }}">
    </div>
    <div class="mb-3">
        <label for="NOMEFANTASIA" class="form-label">Nome Fantasia</label>
        <input type="text" class="form-control" id="NOMEFANTASIA" name="NOMEFANTASIA" value="{{ $coligada->NOMEFANTASIA }}">
    </div>
    <div class="mb-3">
        <label for="APELIDO" class="form-label">Apelido</label>
        <input type="text" class="form-control" id="APELIDO" name="APELIDO" value="{{ $coligada->APELIDO }}">
    </div>
    <div class="mb-3">
        <label for="CGC" class="form-label">CGC</label>
        <input type="text" class="form-control" id="CGC" name="CGC" data-mask="00.000.000/0000-00" value="{{ $coligada->CGC }}">
    </div>
    <div class="mb-3">
        <label for="TELEFONE" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="TELEFONE" name="TELEFONE" data-mask="(00) 0000-0000"  value="{{ $coligada->TELEFONE }}">
    </div>
    <div class="mb-3">
        <label for="CELULAR" class="form-label">Celular</label>
        <input type="text" class="form-control" id="CELULAR" name="CELULAR" data-mask="(00) 0000-0000" value="{{ $coligada->CELULAR }}">
    </div>
    <div class="mb-3">
        <label for="EMAIL" class="form-label">Email</label>
        <input type="text" class="form-control" id="EMAIL" name="EMAIL" value="{{ $coligada->EMAIL }}">
    </div>
    <div class="mb-3">
        <label for="IDIMAGEM" class="form-label">ID Imagem</label>
        <input type="number" class="form-control" id="IDIMAGEM" name="IDIMAGEM" value="{{ $coligada->IDIMAGEM }}">
    </div>
    <div class="mb-3">
        <label for="ATIVO" class="form-label">Ativo</label>
        <select class="form-select" id="ATIVO" name="ATIVO">
            <option value="1" {{ $coligada->ATIVO ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ $coligada->ATIVO ? 'selected' : '' }}>Não</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="CLIENTE" class="form-label">Cliente</label>
        <input type="text" class="form-control" id="CLIENTE" name="CLIENTE" value="{{ $coligada->cliente->NOME }}" disabled>
    </div>
    <div class="text-center">
        <button type="submit" id="btnAtualizar" class="btn-s btn-suc">Atualizar</button>
        <button type="button" class="btn-ajust btn-edi" data-bs-dismiss="modal">Cancelar</button>
    </div>
    <div id="loadingIndicator" class="spinner-border" role="status" style="display: none;">
                <span class="sr-only"></span>
    </div>

</form>

</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha384-HJL3+BOe2iD6DHRK0KjLDoVl6eJ8ipuLyGtLAgq8HGGi5m84pBFXd2Z8yA+4F5eD" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oLlV3vfrU9ziD73ZuJic5ZpVuRUwENuAEl9l5R1g1RI=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+oL6F5f5f5k5F5eLl5d5F5t5f5R5O5y5.5G5v5Q5" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('#form-coligada').submit(function(){
            $('#TELEFONE').unmask();
            $('#CELULAR').unmask();
        });

        $('#TELEFONE').mask('(00) 0000-0000');
        $('#CELULAR').mask('(00) 0000-0000');
        $('#CGC').mask('00.000.000/0000-00');
    });
      $(document).ready(function () {
        $('#btnAtualizar').on('click', function () {
            $(this).hide();
            $('#loadingIndicator').show();
            setTimeout(function () {
                $('#loadingIndicator').hide();
                $('#updateForm').submit();
            }, 6000); 
        });
    });
</script>
</body>
</html>
