<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientHobbies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm.patient_hobbies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->string('description');
            $table->string('location');
            $table->string('frequency');
            $table->string('with_whom');
            $table->string('notes');
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
        Schema::dropIfExists('ccm.patient_hobbies');
    }
}
