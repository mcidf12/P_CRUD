<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();

            $table->string('tipo', 11)->nullable();  
            $table->string('descripcion', 250)->nullable(); 
            $table->bigInteger('folio')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('municipio', 150)->nullable();
            $table->string('poblacion', 250)->nullable();
            $table->date('fechaInicio')->nullable();
            $table->time('horaInicio')->nullable();
            $table->time('horaFin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendas');
    }
};
