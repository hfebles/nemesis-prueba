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
        Schema::create('clients', function (Blueprint $table) {
            $table->id('id_client');
            $table->string('name_client');
            $table->string('idcard_client');
            $table->text('address_client');
            $table->text('complement_address_client');
            $table->string('phone_client')->nullable();
            $table->string('phone2_client')->nullable();
            $table->string('phone3_client')->nullable();
            $table->string('email_client')->nullable();
            $table->string('email2_client')->nullable();
            $table->string('email3_client')->nullable();
            $table->integer('zip_client')->nullable();
            $table->integer('personeria')->nullable();	
            $table->integer('id_state')->nullable();
            $table->integer('comuna')->nullable();
            $table->integer('region')->nullable();
            $table->integer('id_company')->default(0);
            $table->boolean('enabled_client')->default(1);
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
        Schema::dropIfExists('clients');
    }
};
