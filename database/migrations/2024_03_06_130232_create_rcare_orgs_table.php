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
        Schema::create('rcare_orgs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 40)->nullable();
            $table->string('uid', 40)->nullable();
            $table->string('add1', 40)->nullable();
            $table->string('add2', 40)->nullable();
            $table->string('city', 40)->nullable();
            $table->string('state', 40)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 30)->nullable();
            $table->integer('category')->nullable();
            $table->string('contact_person', 40)->nullable();
            $table->string('contact_person_phone', 20)->nullable();
            $table->string('contact_person_email', 30)->nullable();
            $table->string('schema_prefix', 10)->nullable();
            $table->timestamps();
            $table->smallInteger('status')->default(1);
            $table->string('logo_img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_orgs');
    }
};
