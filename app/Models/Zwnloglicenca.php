<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwnloglicenca extends Model
{
    use HasFactory;

    protected $table = 'zwnloglicenca';

    protected $primaryKey = 'IDLOGLIC';

    protected $fillable = [
        'IDCOLIGADA',
        'IDFILIAL',
        'IDTIPOCURSO',
        'IDEMPRESA',
        'IDUSUARIO',
        'IDCLIENTE',
        'IDPRODUTO',
        'QTDALUNOS',
        'QTDCHAMADAS',
        'VERSAOTOTVS',
        'VERSAOWORKNOW',
        'LIBERADO',
        'LIBERADOATE',
        'RECCREATEDBY',
        'RECCREATEDON',
        'RECMODIFIEDBY',
        'RECMODIFIEDON',   
    ];
    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(Zwnempresa::class, 'IDEMPRESA', 'IDEMPRESA');
    }

    public function usuario()
    {
        return $this->belongsTo(Zwnusuario::class, 'IDUSUARIO', 'IDUSUARIO');
    }

    public function cliente()
    {
        return $this->belongsTo(Zwncliente::class, 'IDCLIENTE', 'IDCLIENTE');
    }

    public function coligada()
    {
        return $this->belongsTo(Zwncoligada::class, ['IDCLIENTE', 'IDCOLIGADA'], ['IDCLIENTE', 'IDCOLIGADA']);
    }

    public function produto()
    {
        return $this->belongsTo(Zwnproduto::class, 'IDPRODUTO', 'IDPRODUTO');
    }
}
