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
        Schema::create('rcare_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 50)->nullable();
            $table->string('f_name', 50)->nullable();
            $table->string('l_name', 50)->nullable();
            $table->string('password')->nullable();
            $table->smallInteger('status')->nullable()->default(1);
            $table->boolean('is_lock')->nullable();
            $table->timestamp('lock_time')->nullable();
            $table->timestamps();
            $table->string('remember_me', 100)->nullable();
            $table->string('remember_token')->nullable();
            $table->integer('login_attempt_count')->nullable();
            $table->string('token')->nullable();
            $table->string('role', 100)->nullable();
            $table->string('profile_img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_users');
    }
};
