<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoUsuario extends Model
{
    use HasFactory;
    protected $table = 'tipousuario';
    protected $primaryKey = 'idtipousuario';
    public $timestamps = false;
}
