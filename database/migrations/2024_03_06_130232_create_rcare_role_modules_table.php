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
        Schema::create('rcare_role_modules', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('role_id');
            $table->integer('module_id');
            $table->integer('component_id');
            $table->string('CRUD', 4)->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->boolean('status');

            $table->primary(['role_id', 'module_id', 'component_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_role_modules');
    }
};
