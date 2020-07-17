<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvinciaModel extends Model
{
    protected $table = 'provincia';
    protected $primaryKey  = 'idprovincia';
    public $timestamps = false;
}
