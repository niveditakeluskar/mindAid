<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpmContentTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpm.content_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content_title', 250);
            $table->integer('module_id');
            $table->integer('component_id');
            $table->integer('stage_id'->nullable());
            $table->integer('stage_code')->nullable();
            $table->integer('template_type_id', 20);
            $table->json('content', 20);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('rpm.content_templates');
    }
}
