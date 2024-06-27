<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suspension extends Model
{
    use HasFactory;
    protected $table = 'suspension';
    protected $primaryKey ='idsuspension';
    public $timestamps = false;
    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');

    }
}
