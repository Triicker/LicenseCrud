<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwnusuempresa extends Model
{
    use HasFactory;

    protected $table = 'zwnusuempresas'; 
    protected $primaryKey = 'IDUSUARIOEMPRESA'; 


    protected $fillable = [
        'IDUSUARIO',
        'IDEMPRESA',
        'ATIVO',
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

}
