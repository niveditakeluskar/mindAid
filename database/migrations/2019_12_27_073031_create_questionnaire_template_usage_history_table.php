<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireTemplateUsageHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients.questionnaire_template_usage_history', function (Blueprint $table) {
           
            $table->bigIncrements('id');
            $table->string('contact_via',30);
            $table->integer('module_id');
            $table->integer('template_type');
            $table->string('UID');
            $table->integer('component_id');
            $table->integer('template_id');
            $table->integer('template');
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
        Schema::dropIfExists('patients.questionnaire_template_usage_history');
    }
}
