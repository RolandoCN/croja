<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEmisionModel extends Model
{
    protected $table = 'detalle_emision';
    protected $primaryKey  = 'iddetalle_emision';
    public $timestamps = false;

    //   public function servicio(){
    //     return $this->belongsTo('App\ServicioModel', 'idservicio', 'idservicio');
    // }
}
