<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexos extends Model
{
    use HasFactory;
    protected $table='anexos';
    protected $primaryKey = 'idanexo';
    public function revCorrectiva()
    {
        return $this->belongsTo(RevisionCorrectiva::class, 'id_correccion');

    }
}
