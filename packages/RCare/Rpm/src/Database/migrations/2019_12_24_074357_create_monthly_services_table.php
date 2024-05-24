<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('rpm.monthly_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('practice_id')->nullable();
            $table->string('uid', 20)->unique();
            $table->date('dob')->nullable();
            $table->string('phone_no', 14);
            $table->date('last_encounter')->nullable();
            $table->tinyInteger('patient_data_status')->default(1);
            $table->string('not_recorded_action', 1000)->nullable();
            $table->json('not_recorded_action_template', 1000)->nullable();
            $table->tinyInteger('call_status')->default(1);
            $table->tinyInteger('voice_mail')->default(1);
            $table->tinyInteger('out_of_guideline_patient_condition')->default(1);
            $table->json('out_of_guideline_patient_condition_template', 1000)->nullable();
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
        Schema::dropIfExists('rpm.monthly_services');
    }
}
