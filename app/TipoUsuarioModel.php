<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUsuarioModel extends Model
{
    protected $table = 'tipousuario';
    protected $primaryKey  = 'idtipo_usuario';
    public $timestamps = false;


    public function tipoUsuarioGestion(){
        return $this->hasMany('App\OpcionesModel','idtipo_usuario','idtipo_usuario')->with('ruta');
    }
   
}
