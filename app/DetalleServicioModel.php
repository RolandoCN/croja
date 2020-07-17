<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleServicioModel extends Model
{
    protected $table = 'detalle_servicio';
    protected $primaryKey  = 'iddetalle_servicio';
    public $timestamps = false;

      public function servicio(){
        return $this->belongsTo('App\ServicioModel', 'idservicio', 'idservicio');
    }
}
