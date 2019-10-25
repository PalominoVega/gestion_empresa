<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('tipo_mov',['Caja','Banco']);
            $table->enum('tipo',['Ingreso','Egreso'])->nullable();
            $table->string('descripcion', '100');             
            $table->double('total', '10,2');
            $table->unsignedInteger('concepto_id');
            $table->unsignedInteger('referencia_id');
            $table->enum('estado',['0','1'])->default('0'); //0 bien 1 anulado
            $table->unsignedInteger('user_id');  //user_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimiento');
    }
}
