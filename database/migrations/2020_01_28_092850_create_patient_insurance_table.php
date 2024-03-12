<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_insurance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('code');
            $table->string('ins_id');
            $table->string('ins_type');
            $table->string('ins_provider');
            $table->string('ins_plan');
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
        Schema::dropIfExists('patients.patient_insurance');
    }
}
