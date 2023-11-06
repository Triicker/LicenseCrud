<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwnclicontato extends Model
{
    use HasFactory;

    protected $table = 'zwnclicontatos';
    protected $primaryKey = 'IDCONTATO';

    protected $fillable = [
        'IDCLIENTE',
        'CLIENTE',
        'NOME',
        'APELIDO',
        'TELEFONE',
        'CELULAR',
        'EMAIL',
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
}
