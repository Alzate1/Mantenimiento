<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoInforme extends Model
{
    use HasFactory;
    protected $table = 'tipo_informe';
    protected $primaryKey ='id';
    public $timestamps = false;
}
