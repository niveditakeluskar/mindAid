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
        Schema::create('rcare_menu_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menu');
            $table->string('menu_url');
            $table->integer('service_id');
            $table->string('icon')->nullable();
            $table->integer('parent');
            $table->integer('status');
            $table->timestamps();
            $table->string('sequence')->nullable();
            $table->string('operation', 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_menu_master');
    }
};
