<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientMedicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_medication', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->integer('med_id');
            $table->string('description')->nullable();
            $table->string('purpose')->nullable();
            $table->string('dosage')->nullable();
            $table->string('strength')->nullable();
            $table->string('frequency')->nullable();
            $table->string('route')->nullable(); 
            $table->time('time')->nullable(); 
            $table->string('drug_reaction')->nullable(); 
            $table->string('pharmacogenetic_test')->nullable();             
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
        Schema::dropIfExists('patients.patient_medication');
    }
}
