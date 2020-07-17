<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmisionModel extends Model
{
    protected $table = 'emision';
    protected $primaryKey  = 'idemision';
    public $timestamps = false;

    public function persona(){
        return $this->belongsTo('App\PersonaModel', 'idpersona', 'idpersona');
    }

    public function servicio(){
        return $this->belongsTo('App\ServicioModel', 'idservicio', 'idservicio')->with('detalle');
    }
}
