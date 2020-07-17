<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonaModel extends Model
{
    protected $table = 'persona';
    protected $primaryKey  = 'idpersona';
    public $timestamps = false;

    public function canton(){
        return $this->belongsTo('App\CantonModel', 'idcanton', 'idcanton');
    }
}
