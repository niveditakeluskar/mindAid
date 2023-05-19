<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRcareLicencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rcare_admin.rcare_licences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('org_id');
            $table->Integer('service_id');
            $table->string('license_key', 256)->nullable()->default(Null);
            $table->char('license_model', 4)->nullable()->default(Null);
            $table->Integer('subscription_in_months');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->tinyInteger('status')->default('1');
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
        Schema::dropIfExists('rcare_admin.rcare_licences');
    }
}
