<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Informe extends Model
{
    use HasFactory;
    protected $table = "informe";
    protected $primaryKey = "idinforme";
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo','idvehiculo');
    }
    public function tipo_informe()
    {
        return $this->belongsTo(TipoInforme::class, 'id_tipo_informe','id');
    }
    public function items()
    {
        return $this->hasMany(InformeItem::class, 'id_informe');
    }
}
