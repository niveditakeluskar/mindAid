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
        Schema::create('rcare_users_login_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('login_time');
            $table->integer('userid')->nullable();
            $table->macAddress('mac_address')->nullable();
            $table->ipAddress('ip_address');
            $table->boolean('login_attempt_status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->string('user_email')->nullable();
            $table->timestamp('logout_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcare_users_login_history');
    }
};
