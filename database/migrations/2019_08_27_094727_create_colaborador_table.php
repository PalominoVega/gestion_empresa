<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColaboradorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colaborador', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dni',8);
            $table->string('nombre',50);
            $table->string('apellido',50);
            $table->string('email',50);
            $table->string('direccion',150);
            $table->string('telefono',50);
            $table->string('telefono2',50);
            $table->string('profesion',50);
            $table->date('fecha_nacimiento')->nullable();
            $table->unsignedInteger('puesto_id');
            $table->unsignedInteger('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colaborador');
    }
}
