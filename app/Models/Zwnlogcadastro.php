<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwnlogcadastro extends Model
{
    use HasFactory;

    protected $table = 'zwnlogcadastro';

    protected $primaryKey = 'IDLOGCAD';

    protected $fillable = [
        'IDEMPRESA',
        'IDUSUARIO',
        'CADASTRO',
        'VALORANTERIOR',
        'VALORNOVO',
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
