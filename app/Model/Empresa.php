<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table="empresa";
    public $timestamps = false;

    public function configuracion_facturacion()
    {
        return $this->belongsTo('App\Model\ConfiguracionFacturacion');
    }
}
