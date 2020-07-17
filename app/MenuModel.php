<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey  = 'idmenu';
    public $timestamps = false;

    public function ruta(){
        return $this->hasMany('App\RutaModel','idmenu','idmenu')->with('opciones');
    }

   
}
