<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ren_core.providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('provider_type_id');
            $table->string('provider_name');
            $table->integer('practice_id');
            $table->integer('phone_no');
            $table->string('address');
            $table->string('email_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('ren_core.providers');
    }
}
