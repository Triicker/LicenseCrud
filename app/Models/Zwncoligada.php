<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwncoligada extends Model
{
    use HasFactory;

    protected $table = 'zwncoligada';
    protected $primaryKey = 'IDCOLIGADA';
    public $incrementing = false; 


    protected $fillable = [
        'IDCLIENTE',
        'NOME',
        'NOMEFANTASIA',
        'CGC',
        'EMAIL',
        'TELEFONE',
        'APELIDO',
        'IDIMAGEM',
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

    public function licenca()
    {
        return $this->belongsTo(Zwncoliglicenca::class, 'IDCOLIGADA', 'IDCOLIGADA');
    }

    
}
