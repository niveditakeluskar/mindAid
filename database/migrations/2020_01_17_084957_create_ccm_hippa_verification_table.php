<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcmHippaVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm.ccm_hippa_verification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->date('v_date');
            $table->string('notes')->nullable();
            $table->tinyInteger('verification')->default(1);
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
        Schema::dropIfExists('ccm.ccm_hippa_verification');
    }
}
