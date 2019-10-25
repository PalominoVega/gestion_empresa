<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table='producto';
    public $timestamps = false;
    public function kardex()
    {
        return $this->hasMany('App\Model\Kardex');
    }
    public function stock()
    {
        return $this->hasOne('App\Model\Kardex')
            ->orderBy('id','DESC');
    }
}
