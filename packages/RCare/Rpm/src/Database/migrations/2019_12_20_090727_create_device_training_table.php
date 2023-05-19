<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpm.device_training', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('practice_id')->nullable();
            $table->string('UID',255)->nullable();
            $table->integer('device_id');
            $table->integer('download_protocol_completed')->nullable();
            $table->integer('usage_instruction_completed')->nullable();
            $table->integer('device_training_completed')->nullable();
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
        Schema::dropIfExists('rpm.device_training');
    }
}
