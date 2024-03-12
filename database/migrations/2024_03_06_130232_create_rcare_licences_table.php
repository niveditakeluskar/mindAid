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
        Schema::create('rcare_licences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('org_id');
            $table->integer('service_id');
            $table->string('license_key', 256)->nullable();
            $table->char('license_model', 4)->nullable();
            $table->integer('subscription_in_months');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->smallInteger('status')->default(1);
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
        Schema::dropIfExists('rcare_licences');
    }
};
