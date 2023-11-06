<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwnempresa extends Model
{
    use HasFactory;

    protected $table = 'zwnempresa'; 

    protected $primaryKey = 'IDEMPRESA';


    protected $fillable = [
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
