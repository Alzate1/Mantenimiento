<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emisiones extends Model
{
    use HasFactory;
    protected $table = 'emisiones_contaminantes';
    protected $primaryKey ='idemisiones';
    public $timestamps = false;
    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');
    }
}
