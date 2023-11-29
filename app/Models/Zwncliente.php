<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwncliente extends Model
{
    use HasFactory;

    protected $table = 'zwnclientes'; 
    protected $primaryKey = 'IDCLIENTE';

    protected $fillable = [
        'IDEMPRESA',
        'NOME',
        'APELIDO',
        'ATIVO',
        'EMPRESA',
        'DIASLICENCA',
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

    public function contatos()
    {
        return $this->hasMany(Zwnclicontato::class, 'IDCLIENTE', 'IDCLIENTE');
    }
}
