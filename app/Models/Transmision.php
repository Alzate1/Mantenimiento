<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transmision extends Model
{
    use HasFactory;
    protected $table = 'transmision';
    protected $primaryKey ='idtransmision';
    public $timestamps = false;
    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');

    }
}
