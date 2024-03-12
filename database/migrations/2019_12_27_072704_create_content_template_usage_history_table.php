<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTemplateUsageHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpm.content_template_usage_history', function (Blueprint $table) {
           
            $table->bigIncrements('id');
            $table->string('contact_via',30);
            $table->string('UID',255);
            $table->integer('template_type');
            $table->integer('module_id');
            $table->integer('component_id');
            $table->json('template');
            $table->integer('template_id');
            $table->integer('stage_id');
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
        Schema::dropIfExists('rpm.content_template_usage_history');
    }
}
