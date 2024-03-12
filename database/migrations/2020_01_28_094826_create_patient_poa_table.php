<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientPoaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_poa', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->smallInteger('age');
            $table->string('relationship');
            $table->string('mobile');
            $table->string('phone_2');
            $table->string('email');
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
        Schema::dropIfExists('patients.patient_poa');
    }
}
