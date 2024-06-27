<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $table = "caja_velocidades";
    protected $primaryKey = 'idvelocidades';
    public $timestamps = false;

    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');
    }

}
