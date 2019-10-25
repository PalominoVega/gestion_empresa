<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table="detalle_venta";
    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo('App\Model\Producto');
    }

    public function venta()
    {
        return $this->belongsTo('App\Model\Venta');
    }
}
