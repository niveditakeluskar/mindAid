<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientTimeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpm.patient_time_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('UID',20);
            $table->date('record_date')->nullable();
            $table->integer('module_id');
            $table->integer('component_id');
            $table->time('timer_on');
            $table->time('timer_off');
            $table->time('net_time');
            $table->char('billable',1)->default(1);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpm.patient_time_records');
    }
}
