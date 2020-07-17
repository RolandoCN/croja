<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CantonModel extends Model
{
    protected $table = 'canton';
    protected $primaryKey  = 'idcanton';
    public $timestamps = false;

    public function provincia(){
        return $this->belongsTo('App\ProvinciaModel', 'idprovincia', 'idprovincia');
    }
}
