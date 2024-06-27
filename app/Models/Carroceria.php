<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carroceria extends Model
{
    use HasFactory;
    protected $table = "carroceria";
    protected $primaryKey = 'idcarroceria ';
    public $timestamps = false;
    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');
    }
}
