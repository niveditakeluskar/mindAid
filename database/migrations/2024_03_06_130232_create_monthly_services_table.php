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
        Schema::create('monthly_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid', 20)->unique();
            $table->integer('practice_id')->nullable();
            $table->integer('physician_id')->nullable();
            $table->date('dob');
            $table->string('phone_primary', 14);
            $table->string('chronic_condition', 50);
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
        Schema::dropIfExists('monthly_services');
    }
};
