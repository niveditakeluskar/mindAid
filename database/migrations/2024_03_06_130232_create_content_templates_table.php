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
        Schema::create('content_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description', 250);
            $table->integer('module_id');
            $table->integer('component_id');
            $table->integer('stage_id');
            $table->integer('stage_code');
            $table->string('template_type', 20);
            $table->json('content');
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->smallInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_templates');
    }
};
