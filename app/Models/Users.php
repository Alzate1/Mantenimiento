<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
class Users extends Model implements Authenticatable
{
    use HasFactory,Notifiable,AuthenticatableTrait;
    protected $table = 'usuario';
    protected $primaryKey = 'idusuario';

    protected $fillable = [
        'estado',
        'pass',
        'usuario',
        'correo'
    ];
    protected $hidden = [
        'pass',
    ];
    public function tipoUsuario()
    {
        return $this->belongsTo(tipoUsuario::class, 'idtipo_usuario', 'idtipousuario');
    }

}


