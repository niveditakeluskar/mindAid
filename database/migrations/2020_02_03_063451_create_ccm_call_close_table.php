<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcmCallCloseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm.ccm_call_close', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->date('rec_date');
            $table->tinyInteger('query1')->nullable();
            $table->string('q1_notes')->nullable();
            $table->tinyInteger('query2')->nullable();
            $table->string('q2_notes')->nullable();
            $table->date('q2_date')->nullable();
            $table->string('monthly_notes')->nullable();
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
        Schema::dropIfExists('ccm.ccm_call_close');
    }
}
