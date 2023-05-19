<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientHealthcareServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_healthcare_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->integer('hid');
            $table->string('type')->nullable();
            $table->string('From_whom')->nullable();
            $table->string('location')->nullable();
            $table->string('purpose')->nullable();
            $table->string('frequecy')->nullable(); 
            $table->string('duration')->nullable(); 
            $table->string('notes')->nullable();                            
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
        Schema::dropIfExists('patient_healthcare_services');
    }
}
