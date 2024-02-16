<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.pet', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->string('uid');
            $table->string('pet_name');
            $table->string('pet_type');
            $table->string('notes')->nullabe();;
            $table->intger('created_by')->nullabe();;
            $table->integer('updated_by');
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
        Schema::dropIfExists('patients.pet');
    }
}
