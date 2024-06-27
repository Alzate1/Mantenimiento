<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformeItem extends Model
{
    use HasFactory;
    protected $table = 'informe_item';
    protected $fillable = ['id_informe', 'id_item', 'estado'];
    protected $primaryKey = "id";
    public $timestamps = false;
    // Relación con el informe
    public function informe()
    {
        return $this->belongsTo(Informe::class, 'idinforme');
    }

    // Relación con el item
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
