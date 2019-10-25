<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table="venta";

    public function cliente()
    {
        return $this->belongsTo('App\Model\Cliente')
            ->select('id','documento','nombre','tipo');
    }
    public function user()
    {
        return $this->belongsTo('App\Model\User')
            ->select('id','apellido','nombre');
    }

    public function detalles()
    {
        return $this->hasMany('App\Model\DetalleVenta');
    }
    public function empresa()
    {
        return $this->belongsTo('App\Model\Empresa');
    }
}
