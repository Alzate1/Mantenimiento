<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{
    use HasFactory;
    protected $table='documentos';
    protected $primaryKey = 'iddocumento';
  
    protected $fillable = [
        // ... otros campos ...
        'tarjeta_propiedad',
        'idvehiculo'
    ];
     public function vehiculo()
     {
         return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
     }
}
