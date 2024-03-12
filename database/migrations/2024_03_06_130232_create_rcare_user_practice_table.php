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
        Schema::create('rcare_user_practice', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('practice_id');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->boolean('status');

            $table->primary(['user_id', 'practice_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_user_practice');
    }
};
