<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_family', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->integer('seq');
            $table->smallInteger('age');
            $table->string('relationship');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('moblie');
            $table->string('phone_2');
            $table->string('email');
            $table->date('created_on');
            $table->date('updated_on');
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
        Schema::dropIfExists('patients.patient_family');
    }
}
