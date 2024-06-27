<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'item';
    protected $primaryKey = "id";
    public function informes()
    {
        return $this->belongsToMany(Informe::class, 'informe_item', 'id_item', 'idinforme')
                    ->withPivot('estado');
    }

    // Relación con los mantenimientos
    public function mantenimientos()
    {
        return $this->belongsToMany(Mantenimiento::class, 'mantenimiento_item', 'id_item', 'id_mantenimiento')
                    ->withPivot('estado');
    }
}
