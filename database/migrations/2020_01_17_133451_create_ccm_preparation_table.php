<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcmPreparationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm.ccm_preparation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->date('prep_date');
            $table->tinyInteger('condition_requirnment')->default(1);
            $table->string('condition_requirnment_notes')->nullable();
            $table->tinyInteger('newofficevisit')->default(1);
            $table->string('nov_notes')->nullable();
            $table->tinyInteger('newdiagnosis')->default(1);
            $table->string('nd_notes')->nullable();
            $table->tinyInteger('report_requirnment')->default(1);
            $table->string('report_requirnment_notes')->nullable();
            $table->tinyInteger('newdme')->default(1);
            $table->string('dme_notes')->nullable();
            $table->tinyInteger('changetodme')->default(1);
            $table->string('ctd_notes')->nullable();
            $table->tinyInteger('med_added_or_discon')->default(1);
            $table->string('med_added_or_discon_notes')->nullable();
            $table->string('anything_else')->nullable();
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
        Schema::dropIfExists('ccm.ccm_preparation');
    }
}
