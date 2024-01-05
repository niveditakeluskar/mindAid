<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientContactTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.patient_contact_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id')->nullable();
            $table->tinyInteger('mon_0')->default(0);
            $table->tinyInteger('mon_1')->default(0);
            $table->tinyInteger('mon_2')->default(0);
            $table->tinyInteger('mon_3')->default(0);
            $table->tinyInteger('mon_any')->default(0);
            $table->tinyInteger('tue_0')->default(0);
            $table->tinyInteger('tue_1')->default(0);
            $table->tinyInteger('tue_2')->default(0);
            $table->tinyInteger('tue_3')->default(0);
            $table->tinyInteger('tue_any')->default(0);
            $table->tinyInteger('wed_0')->default(0);
            $table->tinyInteger('wed_1')->default(0);
            $table->tinyInteger('wed_2')->default(0);
            $table->tinyInteger('wed_3')->default(0);
            $table->tinyInteger('wed_any')->default(0);
            $table->tinyInteger('thu_0')->default(0);
            $table->tinyInteger('thu_1')->default(0);
            $table->tinyInteger('thu_2')->default(0);
            $table->tinyInteger('thu_3')->default(0);
            $table->tinyInteger('thu_any')->default(0);
            $table->tinyInteger('fri_0')->default(0);
            $table->tinyInteger('fri_1')->default(0);
            $table->tinyInteger('fri_2')->default(0);
            $table->tinyInteger('fri_3')->default(0);
            $table->tinyInteger('fri_any')->default(0);
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
        Schema::dropIfExists('patients.patient_contact_times');
    }
}
