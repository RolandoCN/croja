<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmisionModel extends Model
{
    protected $table = 'emision';
    protected $primaryKey  = 'idemision';
    protected $appends = ['idemision_encrypt'];
    public $timestamps = false;


     //para retornar el id de la tabla encriptado
    public function getIdemisionEncryptAttribute(){
        return encrypt($this->attributes['idemision']);
    }


    public function persona(){
        return $this->belongsTo('App\PersonaModel', 'idpersona', 'idpersona');
    }

    public function servicio(){
        return $this->belongsTo('App\ServicioModel', 'idservicio', 'idservicio')->with('detalle');
    }
}
