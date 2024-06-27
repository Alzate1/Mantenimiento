<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;
    protected $table = 'revision';
    protected $primaryKey ='idrevision';


    public function cabina(){
        return $this->hasOne(Cabina::class,'id_revision');
    }
    public function caja()
    {
        return $this->hasOne(Caja::class, 'id_revision');
    }
    public function carroceria()
    {
        return $this->hasOne(Carroceria::class, 'id_revision');
    }
    public function emisiones()
    {
        return $this->hasOne(Emisiones::class, 'id_revision');
    }
    public function equipoCarr()
    {
        return $this->hasOne(EquipoCarretera::class, 'id_revision');
    }
    public function frenos()
    {
        return $this->hasOne(Frenos::class, 'id_revision');
    }
    public function luces()
    {
        return $this->hasOne(Luces::class, 'id_revision');
    }
    public function motor()
    {
        return $this->hasOne(Motor::class, 'id_revision');
    }
    public function observacion()
    {
        return $this->hasOne(Observaciones::class, 'id_revision');
    }
    public function suspension()
    {
        return $this->hasOne(Suspension::class, 'id_revision');
    }
    public function transmision()
    {
        return $this->hasOne(Transmision::class, 'id_revision');
    }
    public function revCorrectiva()
    {
        return $this->hasOne(RevisionCorrectiva::class, 'id_revision');
    }
    // 315233333
   
    public function vehiculo() {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }
    public function usuario()
    {
        return $this->belongsTo(Users::class, 'id_usuario');
    }
    // public function documentos()
    // {
    //     return $this->hasOne(Documentos::class, 'id_revision');
    // }
        public function motorista()
    {
        return $this->belongsTo(Motorista::class, 'id_motorista');
    }
}



