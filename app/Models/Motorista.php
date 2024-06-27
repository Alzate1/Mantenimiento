<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motorista extends Model
{
    protected $table ='motorista';
    protected $primaryKey  ='idmotorista';
    use HasFactory;
    public function vehiculo()
     {
         return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
     }
}
