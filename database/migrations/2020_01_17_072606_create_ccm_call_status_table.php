<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcmCallStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm.ccm_call_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->date('rec_date');
            $table->string('phone_no')->nullable();
            $table->tinyInteger('call_continue_status')->nullable();
            $table->tinyInteger('call_status')->default(1);
            $table->json('call_action_template')->nullable();
            $table->tinyInteger('voice_mail')->default(1)->nullable();
            $table->date('call_followup_date')->nullable();
            $table->string('text_msg')->nullable();
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
        Schema::dropIfExists('ccm.ccm_call_status');
    }
}
