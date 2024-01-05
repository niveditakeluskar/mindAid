<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcmTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm.ccm_topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('UID');
            $table->date('record_date');
            $table->tinyInteger('status')->default(1);
            $table->string('topic');
            $table->text('notes')->nullable();
            $table->text('action_taken')->nullable();
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
        Schema::dropIfExists('ccm.ccm_topics');
    }
}
