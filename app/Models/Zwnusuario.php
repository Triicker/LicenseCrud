<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Zwnusuario extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'zwnusuarios'; 

    protected $primaryKey = 'IDUSUARIO';

    protected $fillable = [
        'NOME',
        'APELIDO',
        'USUARIO',
        'SENHA',
        'EMAIL',
        'ATIVO',
        'RECCREATEDBY',
        'RECCREATEDON',
        'RECMODIFIEDBY',
        'RECMODIFIEDON',
    ];
    public $timestamps = false; 

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'SENHA',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'EMAIL_verified_at' => 'datetime',
        'SENHA' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    
    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    // public function getJWTCustomClaims()
    // {
    //     return [];
    // }

    // public function getAuthIdentifierName()
    // {
    //     return 'IDUSUARIO'; // Nome da coluna que representa o ID do usuário
    // }

    
    public function getAuthIdentifier()
    {
        return $this->USUARIO;
    }

    public function getAuthPassword()
    {
        return $this->SENHA; 
    }
    
    // // Métodos adicionais da interface Authenticatable
    // public function getRememberToken()
    // {
    //     // Se você não precisa do recurso "remember me", pode deixar este método vazio.
    // }

    // public function setRememberToken($value)
    // {
    //     // Se você não precisa do recurso "remember me", pode deixar este método vazio.
    // }

    // public function getRememberTokenName()
    // {
    //     // Se você não precisa do recurso "remember me", pode deixar este método vazio.
    // }
}
