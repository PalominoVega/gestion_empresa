<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('codigo',5);
            $table->string('nombre',100);
            $table->double('precio', 9, 3)->nullable();
            $table->integer('punto_reorden')->nullable();
            $table->enum('estado',['0','1'])->default('0');            
            $table->unsignedInteger('empresa_id')->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto');
    }
}
