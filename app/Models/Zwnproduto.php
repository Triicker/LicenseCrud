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
        'IDEMPRESA',
        'NOME',
        'APELIDO',
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

    public function licença()
    {
        return $this->hasMany(Zwncoliglicenca::class, 'IDPRODUTO', 'IDPRODUTO');
    }

}
