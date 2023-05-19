<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientDemographicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_demographics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->tinyInteger('gender');
            $table->string('marital_status');
            $table->string('education');
            $table->string('ethnicity');
            $table->char('height');
            $table->string('weight');
            $table->string('occupation');
            $table->string('employer');
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
        Schema::dropIfExists('patients.patient_demographics');
    }
}
