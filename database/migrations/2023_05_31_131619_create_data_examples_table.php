<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_examples', function (Blueprint $table) {
            $table->id();
            $table->string('id_tipo_doc');
            $table->string('nombre');
            $table->string('rut');
            $table->string('id_contrato');
            $table->string('codigo_rnut');
            $table->string('tipo_doc');
            $table->string('num_doc');
            $table->string('fec_emi');
            $table->string('fec_ven');
            $table->string('monto');
            $table->string('interes');
            $table->string('gasto_cob');
            $table->string('porc_imp');
            $table->string('total_doc');
            $table->string('personeria');
            $table->string('fono1');
            $table->string('fono2');
            $table->string('fono3');
            $table->string('email1');
            $table->string('email2');
            $table->string('email3');
            $table->string('direccion');
            $table->string('complemento_dir');
            $table->string('comuna');
            $table->string('region');
            $table->string('mora');
            $table->string('tramo_mora');
            $table->string('tramo_monto');
            $table->string('ece');
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
        Schema::dropIfExists('data_examples');
    }
}
