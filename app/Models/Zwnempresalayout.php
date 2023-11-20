<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwnempresalayout extends Model
{
    use HasFactory;

    protected $table = 'zwnempresalayout';
    protected $primaryKey = 'IDEMPRESA';

    protected $fillable = [
        'LOGOFUNDO',
        'LOGOFONT',
        'MENUSUP',
        'MENUSUPHOVER',
        'MENUSUPFONT',
        'MENUSUPFONTHOVER',
        'MENULAT',
        'MENULATHOVER',
        'MENULATFONT',
        'MENULATFONTHOVER',
        'MENUROD',
        'MENURODHOVER',
        'MENURODFONT',
        'MENURODFONTHOVER',
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


}
