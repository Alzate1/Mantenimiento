<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frenos extends Model
{
    use HasFactory;
    protected $table = 'frenos';
    protected $primaryKey ='idfrenos';
    public $timestamps = false;
    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');
    }
}
