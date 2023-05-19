<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientVitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_vitals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->date('rec_date');
            $table->integer('height');  
            $table->integer('weight');   
            $table->integer('bmi');    
            $table->integer('bp');        
            $table->integer('o2');       
            $table->integer('pulse_rate');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('patient.patient_vitals');
    }
}
