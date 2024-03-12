<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientEnrollmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_enrollment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('practice_id');
            $table->string('UID',255);
            $table->smallInteger('action');
            $table->json('action_template', 20);
            $table->smallInteger('call_status');
            $table->smallInteger('enrollment_response');
            $table->string('callback_time');
            $table->date('callback_date');
            $table->smallInteger('voice_mail');
            $table->json('enrollment_checklist',50);
            $table->json('finalization_checklist',50);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('patients.patient_enrollment');
    }
}
