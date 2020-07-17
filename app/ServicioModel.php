<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicioModel extends Model
{
    protected $table = 'servicio';
    protected $primaryKey  = 'idservicio';
    public $timestamps = false;
     public function detalle(){
        return $this->hasMany('App\DetalleServicioModel', 'idservicio', 'idservicio');
    }

}
