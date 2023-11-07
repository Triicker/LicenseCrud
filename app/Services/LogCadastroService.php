<?php

namespace App\Services;

use App\Models\Zwnlogcadastro;

class LogCadastroService
{
    public function logCadastro($acao, $cadastro, $valorAnterior, $valorNovo, $user)
    {
        $log = new Zwnlogcadastro();
        $log->IDUSUARIO = $user->IDUSUARIO;
        $log->CADASTRO = $cadastro;
        $log->VALORANTERIOR = $valorAnterior;
        $log->VALORNOVO = $valorNovo;
        $log->RECCREATEDBY = $user->USUARIO;
        $log->RECCREATEDON = now();

        $log->save();

        return $log;
    }
}
