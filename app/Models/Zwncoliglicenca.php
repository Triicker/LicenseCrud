<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwncoliglicenca extends Model
{
    use HasFactory;

    protected $table = 'zwncoliglicenca'; 

    protected $primaryKey = 'IDCOLIGADA';

    protected $fillable = [
        'IDCOLIGADA',
        'IDCLIENTE',
        'IDPRODUTO',
        'DTINICIO',
        'DTFIM',
        'ATIVO',
        'RECCREATEDBY',
        'RECCREATEDON',
        'RECMODIFIEDBY',
        'RECMODIFIEDON',
    ];

    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo(Zwncliente::class, 'IDCLIENTE', 'IDCLIENTE');
    }

    public function coligada()
    {
        return $this->belongsTo(Zwncoligada::class, 'IDCOLIGADA', 'IDCOLIGADA');
    }

    public function produto()
    {
        return $this->belongsTo(Zwnproduto::class, 'IDPRODUTO', 'IDPRODUTO');
    }
}
