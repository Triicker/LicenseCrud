<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwnloglicencaitem extends Model
{
    use HasFactory;

    protected $table = 'zwnloglicencaitem';

    protected $primaryKey = 'IDLOGLICITM';

    protected $fillable = [
        'IDLOGLIC',
        'INFO',
        'VALOR',
        'RECCREATEDBY',
        'RECCREATEDON',
        'RECMODIFIEDBY',
        'RECMODIFIEDON',   
    ];
    public $timestamps = false;

    public function loglicenca()
    {
        return $this->belongsTo(Zwnloglicenca::class, 'IDLOGLIC', 'IDLOGLIC');
    }
}
