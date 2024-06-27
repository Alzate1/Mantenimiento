<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observaciones extends Model
{
    use HasFactory;
    protected $table = 'observaciones';
    protected $primaryKey ='idobservacion';
    public $timestamps = false;

    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');

    }
}
