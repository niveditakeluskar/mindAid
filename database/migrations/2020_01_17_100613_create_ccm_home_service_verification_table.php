<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcmHomeServiceVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm.ccm_home_service_verification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->date('v_date');
            $table->string('query1')->nullable();
            $table->tinyInteger('therapist_come_home_care');
            $table->string('notes')->nullable();
            $table->string('query2')->nullable();
            $table->tinyInteger('home_service_ends');
            $table->tinyInteger('wound_care')->nullable();
            $table->tinyInteger('Injections_IV')->nullable();
            $table->tinyInteger('catheter')->nullable();
            $table->tinyInteger('tubefeeding')->nullable();
            $table->tinyInteger('physio')->nullable();
            $table->tinyInteger('oc_therapy')->nullable();
            $table->tinyInteger('speech_therapy')->nullable();
            $table->string('reason_for_visit')->nullable();
            $table->date('service_end_date')->nullable();
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
        Schema::dropIfExists('ccm.ccm_home_service_verification');
    }
}
