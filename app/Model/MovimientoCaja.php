<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table='movimiento_caja';
    public function caja()
    {
        return $this->belongsTo('App\Model\Caja');
    }
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
