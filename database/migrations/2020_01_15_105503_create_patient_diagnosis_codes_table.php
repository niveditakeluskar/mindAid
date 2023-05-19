<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientDiagnosisCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_diagnosis_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->smallInteger('code');
            $table->string('condition')->nullable();
            $table->string('symptoms')->nullable();
            $table->string('goals')->nullable();
            $table->string('tasks')->nullable();
            $table->string('comments')->nullable();
            $table->date('created_on')->nullable();
            $table->date('updated_on')->nullable();
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
        Schema::dropIfExists('patients.patient_diagnosis_codes');
    }
}
