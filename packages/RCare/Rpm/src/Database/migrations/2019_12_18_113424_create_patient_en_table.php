<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientEnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_time_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seq');
            $table->date('record_date');
            $table->integer('stage_id');
            $table->time('timer_on');
            $table->time('timer_off');
            $table->time('net_time');
            $table->tinyInteger('billable');
            $table->integer('module_id');
            $table->integer('component_id');
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
        Schema::dropIfExists('patients.patient_time_records');
    }
}
