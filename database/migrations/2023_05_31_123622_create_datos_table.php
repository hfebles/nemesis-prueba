<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tipo_doc')->nullable();
            $table->integer('id_contrato')->nullable();
            $table->integer('codigo_rnut')->nullable();
            $table->integer('tipo_doc');
            $table->integer('num_doc');
            $table->date('fec_emi');
            $table->date('fec_ven');
            $table->float('monto', 8, 2)->default(0);
            $table->float('interes', 8, 2)->default(0);
            $table->float('gasto_cob',8, 2)->default(0);
            $table->float('porc_imp')->default(0);
            $table->float('total_doc')->default(1);
            $table->float('mora');
            $table->string('tramo_mora');
            $table->string('tramo_monto');
            $table->integer('id_cliente');
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
        Schema::dropIfExists('datos');
    }
}
