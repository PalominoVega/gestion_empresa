<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table='lote';
    public $timestamps = false; 
    public function producto()
    {
        return $this->belongsTo('App\Model\Producto');
    }
}
