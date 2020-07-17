<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpcionesModel extends Model
{
    protected $table = 'opciones';
    protected $primaryKey  = 'idopciones';
    public $timestamps = false;

    public function tipoUsuario(){
        return $this->belongsTo('App\TipoUsuarioModel', 'idtipo_usuario', 'idtipo_usuario');
    }

     public function ruta(){
        return $this->belongsTo('App\RutaModel', 'idruta', 'idruta');
    }

}
