<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwncoligada extends Model
{
    use HasFactory;

    protected $table = 'zwncoligada';
    protected $primaryKey = 'IDCOLIGADA,IDCLIENTE';

    public $incrementing = true;


    protected $fillable = [
        'IDCOLIGADA',
        'IDCLIENTE',
        'NOME',
        'NOMEFANTASIA',
        'CGC',
        'EMAIL',
        'TELEFONE',
        'CELULAR',
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
    
    public function Cnpj()
    {
        return $this->belongsTo(Zwnloglicenca::class, 'IDCOLIGADA', 'IDCOLIGADA');
    }

    public function coligadas()
    {
        return $this->hasMany(Zwncoligada::class, 'IDCLIENTE', 'IDCLIENTE');
    }

    
}
