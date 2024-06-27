<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
        protected $table = 'vehiculo';
        protected $primaryKey = 'idvehiculo';
    protected $fillable=[
        'documento_propietario',
        'numconductor',
        'id_ruta',
        'id_grupo'
    ];
    public function ruta() {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }

    public function revision()
    {
        return $this->hasOne(Revision::class, 'id_vehiculo');
    }
    public function documento()
    {
        return $this->hasOne(Documentos::class, 'id_vehiculo');
    }
    public function motorista()
    {
        return $this->belongsTo(Motorista::class, 'id_motorista');
    }

   

}

