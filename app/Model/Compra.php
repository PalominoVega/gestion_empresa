<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table='compra';

    public function proveedor()
    {
        return $this->belongsTo('App\Model\Proveedor');
    }
}
