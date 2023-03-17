<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';


    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

   
    //Estos campos no saldra en la respuesta JSON
    //Son ocultos porque son datos sensibles
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function UsuarioVerificado(){
        return $this->verified == self::USUARIO_VERIFICADO;
    }
    public function UsuarioNoVerificado(){
        return $this->verified == self::USUARIO_NO_VERIFICADO;
    }

    public function UsuarioAdministrador(){
        return $this->admin == self::USUARIO_ADMINISTRADOR;
    }
    public static function generarverificacionToken(){
        return Str::random(40);
    }
}
