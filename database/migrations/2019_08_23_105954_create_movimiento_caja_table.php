<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientoCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_caja', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('caja_id');                          
            $table->unsignedInteger('user_id');
            $table->double('total',10,3);
            $table->enum('estado',['0','1'])->default('0');
            $table->unsignedInteger('empresa_id');                          
            $table->timestamps();// fecha en que se creo y fecha en que se reporto 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimiento_caja');
    }
}
