<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alistamiento extends Model
{
    use HasFactory;
    protected $table = "alistamiento";
    protected $primaryKey = "idchequeo";
    protected $fillable = [
        'id_vehiculo',
        'id_usuario',
    ];
    public function user() {
        return $this->belongsTo(Users::class, 'id_usuario');
    }

    public function vehiculo() {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }
    public function motorista() {
        return $this->belongsTo(Motorista::class, 'id_motorista');
    }
    public function documento()
    {
        return $this->belongsTo(Documentos::class, 'id_vehiculo', 'id_vehiculo');
    }
}
