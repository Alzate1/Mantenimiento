<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoCarretera extends Model
{
    use HasFactory;
    protected $table = 'equipo_carretera';
    protected $primaryKey ='idequipo';
    public $timestamps = false;
    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');
    }
}

