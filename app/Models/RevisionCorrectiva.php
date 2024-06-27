<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionCorrectiva extends Model
{
    use HasFactory;
    protected $table = 'revision_correctiva';
    protected $primaryKey ='idcorreccion';
    protected $fillable = [
        'id_revision',
    ];
    public function revision()
    {
        return $this->belongsTo(Revision::class, 'id_revision');

    }
    public function anexos(){
        return $this->hasOne(Anexos::class, 'id_correccion');
    }
}
