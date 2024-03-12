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
        Schema::create('rcare_practice_master', function (Blueprint $table) {
            $table->integer('practice_id');
            $table->string('practice', 50)->nullable();
            $table->integer('org_id');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->boolean('status');

            $table->primary(['practice_id', 'org_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_practice_master');
    }
};
