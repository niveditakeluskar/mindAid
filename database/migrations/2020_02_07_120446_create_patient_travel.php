<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientTravel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm.patient_travel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->string('travel_type');
            $table->string('location');
            $table->string('frequency');
            $table->string('with_whom');
            $table->string('notes');
            $table->string('upcoming_trips');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('ccm.patient_travel');
    }
}
