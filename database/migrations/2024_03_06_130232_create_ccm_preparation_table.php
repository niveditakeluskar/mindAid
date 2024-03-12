<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccm_preparation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->date('prep_date');
            $table->smallInteger('condition_requirnment')->default(1);
            $table->string('condition_requirnment_notes')->nullable();
            $table->smallInteger('newofficevisit')->default(1);
            $table->string('nov_notes')->nullable();
            $table->smallInteger('newdiagnosis')->default(1);
            $table->string('nd_notes')->nullable();
            $table->smallInteger('report_requirnment')->default(1);
            $table->string('report_requirnment_notes')->nullable();
            $table->smallInteger('newdme')->default(1);
            $table->string('dme_notes')->nullable();
            $table->smallInteger('changetodme')->default(1);
            $table->string('ctd_notes')->nullable();
            $table->smallInteger('med_added_or_discon')->default(1);
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
        Schema::dropIfExists('ccm_preparation');
    }
};
