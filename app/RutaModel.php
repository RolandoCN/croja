<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RutaModel extends Model
{
    protected $table = 'ruta';
    protected $primaryKey  = 'idruta';
    public $timestamps = false;

    public function menu(){
        return $this->belongsTo('App\MenuModel', 'idmenu', 'idmenu');
    }

     public function opciones(){
        return $this->hasMany('App\OpcionesModel','idruta','idruta')->with('tipoUsuario');
    }
}
