<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwnproduto extends Model
{
    use HasFactory;

    protected $table = 'zwnproduto'; 
    protected $primaryKey = 'IDPRODUTO';

    protected $fillable = [
        'IDPRODUTO',
        'NOME',
        'APELIDO',
        'ATIVO',
        'RECCREATEDBY',
        'RECCREATEDON',
        'RECMODIFIEDBY',
        'RECMODIFIEDON',
    ];
    public $timestamps = false; 

}
